<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Exception\UnprotectedFieldTypeException;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
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
    private ?PropertyAccessorInterface $propertyAccessor = null;
    /** @var array<string, array<string, array<Constraint>>> */
    private array $constraintsCache = [];
    /** @var array<string, array<string, bool>> */
    private array $onlyValidateOnUpdateCache = [];

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
        $propertyAccessor = $this->propertyAccessor ??= PropertyAccess::createPropertyAccessor();

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
     * Constraints only depend on the Doctrine mapping, so they are memoized per class and field.
     * Sharing the constraint instances is safe: constraints are immutable value objects.
     *
     * @return array<Constraint>
     */
    public function getConstraints(string $class, string $field): array
    {
        return $this->constraintsCache[$class][$field] ??= $this->buildConstraints($class, $field);
    }

    /**
     * @return array<Constraint>
     */
    private function buildConstraints(string $class, string $field): array
    {
        $metadata = $this->em->getClassMetadata($class);

        $constraints = [];

        if (array_key_exists($field, $metadata->fieldMappings)) {
            // Normalize the FieldMapping object to the array shape providers expect
            $fieldMapping = get_object_vars($metadata->fieldMappings[$field]);

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

            if ($associationMapping->isToOneOwningSide()) {
                $constraints[] = new Type($associationMapping->targetEntity);
                // Nullable field
                $joinColumn = $associationMapping->joinColumns[0] ?? null;
                if (null !== $joinColumn && false === $joinColumn->nullable) {
                    $constraints[] = new NotNull();
                }
            } elseif ($associationMapping->isOwningSide()) {
                $constraints[] = new All(constraints: [
                    new Type($associationMapping->targetEntity),
                ]);
            }
        } else {
            throw new \LogicException('Unknown field: ' . $class . '::$' . $field);
        }
        return $constraints;
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
        $class = $entity::class;
        $hasOnlyValidateOnUpdate = $this->onlyValidateOnUpdateCache[$class][$field]
            ??= $this->hasOnlyValidateOnUpdateAttribute($class, $field);

        if ($hasOnlyValidateOnUpdate) {
            return in_array($field, array_keys($this->em->getUnitOfWork()->getEntityChangeSet($entity)), true);
        }
        return true;
    }

    /**
     * Whether the field carries the OnlyValidateOnUpdate attribute only depends on the class
     * definition, so it is memoized per class and field to avoid repeated reflection.
     *
     * @param class-string $class
     */
    private function hasOnlyValidateOnUpdateAttribute(string $class, string $field): bool
    {
        $fieldAttributes = $this->getFieldAttributes(new \ReflectionClass($class), $field);

        foreach ($fieldAttributes as $attribute) {
            if (OnlyValidateOnUpdate::class === $attribute->getName()) {
                return true;
            }
        }
        return false;
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
