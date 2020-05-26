<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LatitudeType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LongitudeType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\MoneyType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\PercentType;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\ValidatorBundle\Exception\UnsupportedAssociationFieldException;
use AssoConnect\ValidatorBundle\Exception\UnsupportedFieldException;
use AssoConnect\ValidatorBundle\Exception\UnsupportedScalarFieldException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Locale;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Timezone;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class EntityValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var PropertyAccessorInterface */
    private $propertyAccessor;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($entity, Constraint $constraint)
    {
        $class = get_class($entity);
        $metadata = $this->em->getClassMetadata($class);
        $properties = array_keys($metadata->getReflectionProperties());

        $validator = $this->context
            ->getValidator()
            ->inContext($this->context);

        foreach ($properties as $property) {
            $constraints = $this->getConstraintsForField($metadata, $property);

            if ($constraints) {
                $value = $this->getValue($property);

                // We test the property so we can set the constraints list at runtime.
                // According to https://symfony.com/doc/current/components/validator/resources.html, the constraint
                // list cannot be set at runtime when validating an object but should come from loaders.
                // So another way would be to get rid of this EntityValidator, create a custom loader for Doctrine
                // entity that will define constraints for each property and just call $validator->validate($entity).
                $validator
                    ->atPath($property)
                    ->validate($value, $constraints);
            }
        }
    }

    /**
     * Returns an array of constraint for a given field
     */
    private function getConstraintsForField(ClassMetadata $metadata, string $field): array
    {
        // The field is a scalar one
        if (array_key_exists($field, $metadata->fieldMappings)) {
            $constraints = $this->getConstraintsForType($metadata->fieldMappings[$field]);
            // Nullable field
            if (false === $metadata->fieldMappings[$field]['nullable']) {
                $constraints[] = new NotNull();
            }

            return $constraints;
        }

        // The field is an embedded class
        if (array_key_exists($field, $metadata->embeddedClasses)) {
            return [new Valid()];
        }

        // The field is an association
        if (array_key_exists($field, $metadata->associationMappings)) {
            return $this->getConstraintsForAssociation($metadata, $field);
        }

        throw new UnsupportedFieldException($metadata->name, $field);
    }

    /**
     * Returns the constraints list for an association field
     */
    private function getConstraintsForAssociation(ClassMetadata $metadata, string $field): array
    {
        $fieldMapping = $metadata->associationMappings[$field];

        // Only the owning side is validated

        if (false === $fieldMapping['isOwningSide']) {
            return [];
        }

        if ($fieldMapping['type'] & ClassMetadata::TO_ONE) {
            // ToOne
            $constraints = [
                new Type($fieldMapping['targetEntity'])
            ];
            // Nullable field
            if (isset($fieldMapping['joinColumns'][0]['nullable']) && !$fieldMapping['joinColumns'][0]['nullable']) {
                $constraints[] = new NotNull();
            }
            return $constraints;
        }

        if ($fieldMapping['type'] & ClassMetadata::TO_MANY) {
            // ToMany
            return [new All(
                [
                    'constraints' => [
                        new Type($fieldMapping['targetEntity']),
                    ],
                ]
            )];
        }
        // Unknown
        throw new UnsupportedAssociationFieldException($metadata->name, $field, $fieldMapping['type']);
    }

    private function getConstraintsForType(array $fieldMapping): array
    {
        $constraints = [];

        switch ($fieldMapping['type']) {
            case 'bic':
                $constraints[] = new Bic([
                    'iban' => $this->getValue('iban'),
                ]);
                // Required while we use the Symfony original validator which is too loose
                $constraints[] = new Regex('/^[0-9A-Z]+$/');
                break;
            case 'bigint':
                $constraints[] = new Type('integer');
                if (isset($fieldMapping['options']['unsigned']) && true === $fieldMapping['options']['unsigned']) {
                    $constraints[] = new GreaterThanOrEqual(0);
                    $constraints[] = new LessThanOrEqual(pow(2, 64) - 1);
                } else {
                    $constraints[] = new GreaterThanOrEqual(- pow(2, 63));
                    $constraints[] = new LessThanOrEqual(pow(2, 63) - 1);
                }
                break;
            case 'boolean':
                $constraints[] = new Type('bool');
                break;
            case 'country':
                $constraints[] = new Country();
                break;
            case 'currency':
                $constraints[] = new Currency();
                break;
            case 'date':
            case 'datetime':
            case 'datetimetz':
            case 'datetimeutc':
                $constraints[] = new Type(\DateTime::class);
                break;
            case 'date_absolute':
                $constraints[] = new Type(AbsoluteDate::class);
                break;
            case 'decimal':
                $constraints[] = new Type('float');
                $constraints[] = new GreaterThan(- pow(10, $fieldMapping['precision'] - $fieldMapping['scale']));
                $constraints[] = new LessThan(pow(10, $fieldMapping['precision'] - $fieldMapping['scale']));
                $constraints[] = new FloatScale($fieldMapping['scale']);
                break;
            case 'email':
                $constraints[] = new Email();
                $length = $fieldMapping['length'] ?? 255;
                $constraints[] = new Length(['max' => $length]);
                break;
            case 'float':
                $constraints[] = new Type('float');
                break;
            case 'iban':
                $constraints[] = new Iban();
                // Required while we use the Symfony original validator which is too loose
                $constraints[] = new Regex('/^[0-9A-Z]+$/');
                break;
            case 'integer':
                $constraints[] = new Type('integer');
                break;
            case 'ip':
                $constraints[] = new Ip(['version' => 'all']);
                break;
            case 'json':
                // TODO: implement JSON validation?
                break;
            case 'latitude':
                $constraints[] = new Latitude();
                $constraints[] = new FloatScale($fieldMapping['scale'] ? : LatitudeType::DEFAULT_SCALE);
                break;
            case 'locale':
                $options['canonicalize'] = true;
                $constraints[] = new Locale($options);
                break;
            case 'longitude':
                $constraints[] = new Longitude();
                $constraints[] = new FloatScale($fieldMapping['scale'] ? : LongitudeType::DEFAULT_SCALE);
                break;
            case 'money':
                $constraints[] = new Money();
                $constraints[] = new FloatScale($fieldMapping['scale'] ? : MoneyType::DEFAULT_SCALE);
                break;
            case 'percent':
                $constraints[] = new Percent();
                $constraints[] = new FloatScale($fieldMapping['scale'] ? : PercentType::DEFAULT_SCALE);
                break;
            case 'phone':
                $constraints[] = new Phone();
                break;
            case 'phonelandline':
                $constraints[] = new PhoneLandline();
                break;
            case 'phonemobile':
                $constraints[] = new PhoneMobile();
                break;
            case 'postal':
                $constraints[] = new Postal([
                    'country' => $this->getValue('country'),
                ]);
                break;
            case 'smallint':
                $constraints[] = new Type('integer');
                if (isset($fieldMapping['options']['unsigned']) && true === $fieldMapping['options']['unsigned']) {
                    $constraints[] = new GreaterThan(0);
                    $constraints[] = new LessThanOrEqual(pow(2, 16) - 1);
                } else {
                    $constraints[] = new GreaterThanOrEqual(- pow(2, 15));
                    $constraints[] = new LessThanOrEqual(pow(2, 15) - 1);
                }
                break;
            case 'string':
                $length = $fieldMapping['length'] ?? 255;
                $constraints[] = new Length(['max' => $length]);
                break;
            case 'text':
                $length = $fieldMapping['length'] ?? 65535;
                $constraints[] = new Length(['max' => $length, 'charset' => '8bit']);
                break;
            case 'timezone':
                $constraints[] = new Timezone();
                break;
            case 'uuid':
            case 'uuid_binary_ordered_time':
                $constraints[] = new Uuid();
                break;
            default:
                throw new UnsupportedScalarFieldException($fieldMapping['type']);
        }

        return $constraints;
    }

    private function getValue(string $property)
    {
        // PropertyAccessor will throw an exception if a null value is found on a path
        // (ex: path is date.start but date is NULL)
        try {
            return $this->propertyAccessor->getValue($this->context->getObject(), $property);
        } catch (UnexpectedTypeException $exception) {
            return null;
        }
    }
}
