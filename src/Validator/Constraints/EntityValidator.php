<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Exception\UnprotectedFieldTypeException;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

/**
 * @phpstan-import-type FieldMapping from ClassMetadataInfo
 */
#[\Attribute]
class EntityValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;
    /** @var FieldConstraintsSetProviderInterface[] */
    private iterable $fieldConstraintsSetFactories;

    /**
     * @param FieldConstraintsSetProviderInterface[] $fieldConstraintsSetFactories
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        iterable $fieldConstraintsSetFactories
    ) {
        $this->em = $entityManager;
        $this->fieldConstraintsSetFactories = $fieldConstraintsSetFactories;
    }

    public function validate(mixed $entity, Constraint $constraint): void
    {
        if (!$constraint instanceof Entity) {
            throw new \Symfony\Component\Validator\Exception\UnexpectedTypeException(
                $constraint,
                __NAMESPACE__ . '\Entity'
            );
        }

        Assert::object($entity);
        $class = get_class($entity);
        $metadata = $this->em->getClassMetadata($class);
        $fields = array_keys($metadata->getReflectionProperties());
        $validator = $this->context->getValidator()->inContext($this->context);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($fields as $field) {
            if (!$this->checkIfFieldNeedsToBeValidated($entity, $field)) {
                continue;
            }
            $constraints = $this->getConstraints($class, $field);

            if ([] !== $constraints) {
                // PropertyAccessor will throw an exception if a null value is found on a path
                // (ex: path is date.start but date is NULL)
                try {
                    $value = $propertyAccessor->getValue($entity, $field);
                    if ($value instanceof \BackedEnum) {
                        $value = $value->value;
                    }
                } catch (UnexpectedTypeException $exception) {
                    $value = null;
                }

                $validator->atPath($field)->validate($value, $constraints);
            }
        }
    }

    /**
     * @param FieldMapping $fieldMapping
     * @return list<Constraint>
     */
    public function getConstraintsForType(array $fieldMapping): array
    {
        foreach ($this->fieldConstraintsSetFactories as $fieldConstraintsSetProvider) {
            if ($fieldConstraintsSetProvider->supports($fieldMapping['type'])) {
                return $fieldConstraintsSetProvider->getConstraints($fieldMapping);
            }
        }

        throw UnprotectedFieldTypeException::becauseConstraintsNotFoundForType($fieldMapping['type']);
    }

    /**
     * @return array<Constraint>
     */
    public function getConstraints(string $class, string $field): array
    {
        $metadata = $this->em->getClassMetadata($class);

        $constraints = [];

        if (array_key_exists($field, $metadata->fieldMappings)) {
            $fieldMapping = $metadata->fieldMappings[$field];

            // Nullable field
            Assert::keyExists($fieldMapping, 'nullable');
            if (true !== $fieldMapping['nullable']) {
                $constraints[] = [new NotNull()];
            }

            $constraints[] = $this->getConstraintsForType($fieldMapping);

            $constraints = call_user_func_array('array_merge', $constraints);
        } elseif (array_key_exists($field, $metadata->embeddedClasses)) {
            $constraints[] = new Valid();
        } elseif (array_key_exists($field, $metadata->associationMappings)) {
            $fieldMapping = $metadata->associationMappings[$field];

            if (true === $fieldMapping['isOwningSide']) {
                if (($fieldMapping['type'] & ClassMetadataInfo::TO_ONE) !== 0) {
                    // ToOne
                    $constraints[] = new Type($fieldMapping['targetEntity']);
                    // Nullable field
                    if (
                        isset($fieldMapping['joinColumns'][0]['nullable'])
                        && true !== $fieldMapping['joinColumns'][0]['nullable']
                    ) {
                        $constraints[] = new NotNull();
                    }
                } elseif (($fieldMapping['type'] & ClassMetadataInfo::TO_MANY) !== 0) {
                    // ToMany
                    $constraints[] = new All(
                        [
                            'constraints' => [
                                new Type($fieldMapping['targetEntity']),
                            ],
                        ]
                    );
                } else {
                    // Unknown
                    throw new \DomainException('Unknown type: ' . $fieldMapping['type']);
                }
            }
        } else {
            throw new \LogicException('Unknown field: ' . $class . '::$' . $field);
        }
        return $constraints;
    }

    private function checkIfFieldNeedsToBeValidated(object $entity, string $field): bool
    {
        $reflectionClass = new \ReflectionClass($entity::class);
        $fieldAttributes = $this->getFieldAttributes($reflectionClass, $field);

        foreach ($fieldAttributes as $attribute) {
            if (OnlyValidateOnUpdate::class === $attribute->getName()) {
                return in_array($field, array_keys($this->em->getUnitOfWork()->getEntityChangeSet($entity)), true);
            }
        }
        return true;
    }

    /** @return \ReflectionAttribute[] */
    private function getFieldAttributes(\ReflectionClass $reflectionClass, string $field): array
    {
        if ($reflectionClass->hasProperty($field)) {
            return $reflectionClass->getProperty($field)->getAttributes();
        }

        if (false === $reflectionClass->getParentClass()) {
            return [];
        }
        return $this->getFieldAttributes($reflectionClass->getParentClass(), $field);
    }
}
