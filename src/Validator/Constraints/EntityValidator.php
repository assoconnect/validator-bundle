<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Exception\UnprotectedFieldTypeException;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\AssociationMapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ToOneOwningSideMapping;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

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
        $fields = array_keys(self::reflectionPropertiesToArray($metadata->getReflectionProperties()));
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
                } catch (UnexpectedTypeException $exception) {
                    $value = null;
                }

                $validator->atPath($field)->validate(
                    $value instanceof \BackedEnum ? $value->value : $value,
                    $constraints
                );
            }
        }
    }

    /**
     * @param mixed[] $fieldMapping
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
            $mapping = $metadata->fieldMappings[$field];

            // ORM 3 turns field mappings into objects; normalize to the array shape providers expect
            $fieldMapping = is_array($mapping) ? $mapping : get_object_vars($mapping);

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
            $associationMapping = $metadata->associationMappings[$field];

            if (is_array($associationMapping)) {
                // ORM 2
                $isOwningSide = true === $associationMapping['isOwningSide'];
                $type = $associationMapping['type'];
                $targetEntity = $associationMapping['targetEntity'] ?? null;
                $isJoinColumnNullable = !isset($associationMapping['joinColumns'][0]['nullable'])
                    || true === $associationMapping['joinColumns'][0]['nullable'];
            } else {
                // ORM 3
                [$isOwningSide, $type, $targetEntity, $isJoinColumnNullable] =
                    self::extractAssociationMappingValues($associationMapping);
            }

            if ($isOwningSide) {
                if (($type & ClassMetadata::TO_ONE) !== 0) {
                    // ToOne
                    $constraints[] = new Type($targetEntity);
                    // Nullable field
                    if (!$isJoinColumnNullable) {
                        $constraints[] = new NotNull();
                    }
                } elseif (($type & ClassMetadata::TO_MANY) !== 0) {
                    // ToMany
                    $constraints[] = new All(constraints: [
                        new Type($targetEntity),
                    ]);
                } else {
                    // Unknown
                    throw new \DomainException('Unknown type: ' . $type);
                }
            }
        } else {
            throw new \LogicException('Unknown field: ' . $class . '::$' . $field);
        }
        return $constraints;
    }

    /**
     * ORM 3-only path: coverage depends on the installed ORM major
     * @codeCoverageIgnore
     * @return array{bool, int, string, bool}
     */
    private static function extractAssociationMappingValues(AssociationMapping $associationMapping): array
    {
        $isJoinColumnNullable = true;
        if ($associationMapping instanceof ToOneOwningSideMapping) {
            $joinColumn = $associationMapping->joinColumns[0] ?? null;
            $isJoinColumnNullable = null === $joinColumn || false !== $joinColumn->nullable;
        }

        return [
            $associationMapping->isOwningSide(),
            $associationMapping->type(),
            $associationMapping->targetEntity,
            $isJoinColumnNullable,
        ];
    }

    /**
     * ORM 3.5+ may return a LegacyReflectionFields object instead of an array (native lazy objects).
     * Excluded from coverage: the object path cannot be built without the full metadata factory.
     *
     * @codeCoverageIgnore
     * @param array<string, \ReflectionProperty|null>|\Traversable<string, \ReflectionProperty|null> $properties
     * @return array<string, \ReflectionProperty|null>
     */
    private static function reflectionPropertiesToArray(iterable $properties): array
    {
        return $properties instanceof \Traversable ? iterator_to_array($properties, true) : $properties;
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

    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @return \ReflectionAttribute<object>[]
     */
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
