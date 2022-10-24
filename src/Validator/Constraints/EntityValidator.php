<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\AbsolutePercentValueBundle\Object\AbsolutePercentValue;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LatitudeType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LongitudeType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\MoneyType;
use AssoConnect\PHPDate\AbsoluteDate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Money\Currency as CurrencyObject;
use Money\Money as MoneyObject;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Timezone;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Ulid;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

/**
 * @Annotation
 */
class EntityValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($entity, Constraint $constraint): void
    {
        if (!$constraint instanceof Entity) {
            throw new \Symfony\Component\Validator\Exception\UnexpectedTypeException(
                $constraint,
                __NAMESPACE__ . '\EmployerIdentificationNumber'
            );
        }

        Assert::object($entity);
        $class = get_class($entity);
        $metadata = $this->em->getClassMetadata($class);
        $fields = array_keys($metadata->getReflectionProperties());
        $validator = $this->context->getValidator()->inContext($this->context);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($fields as $field) {
            $constraints = $this->getConstraints($class, $field);

            if ([] !== $constraints) {
                // PropertyAccessor will throw an exception if a null value is found on a path
                // (ex: path is date.start but date is NULL)
                try {
                    $value = $propertyAccessor->getValue($entity, $field);
                } catch (UnexpectedTypeException $exception) {
                    $value = null;
                }

                $validator->atPath($field)->validate($value, $constraints);
            }
        }
    }

    /**
     * @param array<mixed> $fieldMapping
     * @return array<Constraint>
     */
    public function getConstraintsForType(array $fieldMapping): array
    {
        $constraints = [];

        switch ($fieldMapping['type']) {
            case 'absolute_percent_value':
                $constraints[] = new Type(AbsolutePercentValue::class);
                break;
            case 'amount':
            case 'amountAsBigint':
                $constraints[] = new Type(MoneyObject::class);
                break;
            case 'array':
                $constraints[] = new Type('array');
                break;
            case 'bic':
                $constraints[] = new Bic();
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
                $constraints[] = new Type(CurrencyObject::class);
                break;
            case 'date':
            case 'datetime':
            case 'datetimetz':
            case 'datetimeutc':
                $constraints[] = new Type(\DateTime::class);
                break;
            case 'date_immutable':
            case 'datetime_immutable':
            case 'datetimetz_immutable':
            case 'datetimeutc_immutable':
                $constraints[] = new Type(\DateTimeImmutable::class);
                break;
            case 'datetimezone':
                $constraints[] = new Type(\DateTimeZone::class);
                break;
            case 'date_absolute':
                $constraints[] = new Type(AbsoluteDate::class);
                break;
            case 'decimal':
                $constraints[] = new Type('float');
                Assert::keyExists($fieldMapping, 'precision');
                Assert::keyExists($fieldMapping, 'scale');
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
            case 'frenchSiren':
                $constraints[] = new FrenchSiren();
                break;
            case 'frenchRna':
                $constraints[] = new FrenchRna();
                break;
            case 'iban':
                $constraints[] = new Iban();
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
                $constraints[] = new FloatScale($fieldMapping['scale'] ?? LatitudeType::DEFAULT_SCALE);
                break;
            case 'locale':
                $constraints[] = new Locale(['canonicalize' => true]);
                break;
            case 'longitude':
                $constraints[] = new Longitude();
                $constraints[] = new FloatScale($fieldMapping['scale'] ?? LongitudeType::DEFAULT_SCALE);
                break;
            case 'money':
                $constraints[] = new Money();
                $constraints[] = new FloatScale($fieldMapping['scale'] ?? MoneyType::DEFAULT_SCALE);
                break;
            case 'percent':
                $constraints[] = new Type(\AssoConnect\PHPPercent\Percent::class);
                $constraints[] = new Percent();
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
                break;
            case 'simple_array':
                $constraints[] = new Type('array');
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
                if (isset($fieldMapping['nullable']) && true === $fieldMapping['nullable']) {
                    $constraints[] = new NotBlank(['allowNull' => true]);
                }
                break;
            case 'text':
                $length = $fieldMapping['length'] ?? 65535;
                $constraints[] = new Length(['max' => $length, 'charset' => '8bit']);
                break;
            case 'timezone':
                $constraints[] = new Timezone();
                break;
            case 'ulid':
                $constraints[] = new Ulid();
                break;
            case 'uuid':
            case 'uuid_binary_ordered_time':
                $constraints[] = new Uuid();
                break;
            default:
                throw new \DomainException('Unsupported field type: ' . $fieldMapping['type']);
        }

        return $constraints;
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
                if (($fieldMapping['type'] & ClassMetadata::TO_ONE) !== 0) {
                    // ToOne
                    $constraints[] = new Type($fieldMapping['targetEntity']);
                    // Nullable field
                    if (
                        isset($fieldMapping['joinColumns'][0]['nullable'])
                        && true !== $fieldMapping['joinColumns'][0]['nullable']
                    ) {
                        $constraints[] = new NotNull();
                    }
                } elseif (($fieldMapping['type'] & ClassMetadata::TO_MANY) !== 0) {
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
            throw new \LogicException('Unknown field: ' . $class  . '::$' . $field);
        }
        return $constraints;
    }
}
