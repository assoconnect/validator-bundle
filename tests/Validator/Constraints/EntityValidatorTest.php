<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntity;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntityParent;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use AssoConnect\ValidatorBundle\Validator\Constraints\EntityValidator;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use AssoConnect\ValidatorBundle\Validator\Constraints\Percent;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;
use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entity\TestEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Country;
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
use Symfony\Component\Validator\Constraints\Currency as CurrencyConstraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class EntityValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getClassMetadata')->willReturn($this->getMockClassMetadata());

        parent::setUp();
    }

    public function getConstraint($options = []): Constraint
    {
        return new Entity($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        $entityValidator = new EntityValidator($this->entityManager);
        $entityValidator->postalCountryPropertyPath = 'FR';
        return $entityValidator;
    }

    public function testValidateDoctrineEntity()
    {
        $entity = new MyEntity();
        $entity->postal = "59270";
        $entity->country = "FR";

        var_dump($this->validator->validate($entity, $this->getConstraint()));
        exit;
    }

    public function testGetConstraintsForTypeUnknown()
    {
        $fieldMapping = [
            'type' => 'fail'
        ];

        $this->expectException(\DomainException::class);
        $this->validator->getConstraintsForType($fieldMapping);
    }

    /**
     * @dataProvider getConstraintsForTypeProvider
     */
    public function testGetConstraintsForType($fieldMapping, $constraints)
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraintsForType($fieldMapping),
            $constraints
        );
    }

    public function getConstraintsForTypeProvider()
    {
        return [
            [
                ['type' => 'bic'], [new Bic(), new Regex('/^[0-9A-Z]+$/')],
            ],
            [
                ['type' => 'bigint', 'options' => [ 'unsigned' => true]],
                [new Type('integer'), new GreaterThanOrEqual(0), new LessThanOrEqual(pow(2, 64) - 1)],
            ],
            [
                ['type' => 'bigint', 'options' => [ 'unsigned' => false]],
                [new Type('integer'), new GreaterThanOrEqual(- pow(2, 63)), new LessThanOrEqual(pow(2, 63) - 1)],
            ],
            [
                ['type' => 'bigint'],
                [new Type('integer'), new GreaterThanOrEqual(- pow(2, 63)), new LessThanOrEqual(pow(2, 63) - 1)],
            ],
            [
                ['type' => 'boolean'], [new Type('bool')],
            ],
            [
                ['type' => 'country'], [new Country()],
            ],
            [
                ['type' => 'currency'], [new CurrencyConstraint()],
            ],
            [
                ['type' => 'date'], [new Type(\DateTime::class)],
            ],
            [
                ['type' => 'datetime'], [new Type(\DateTime::class)],
            ],
            [
                ['type' => 'datetimetz'], [new Type(\DateTime::class)],
            ],
            [
                ['type' => 'datetimeutc'], [new Type(\DateTime::class)],
            ],
            [
                ['type' => 'date_absolute'], [ new Type(AbsoluteDate::class)],
            ],
            [
                ['type' => 'decimal', 'precision' => 4, 'scale' => 2],
                [
                    new Type('float'),
                    new GreaterThan(- pow(10, 4 - 2)),
                    new LessThan(pow(10, 4 - 2)),
                    new FloatScale(2)
                ],
            ],
            [
                ['type' => 'email', 'length' => 10], [new Email(), new Length(['max' => 10])],
            ],
            [
                ['type' => 'email'], [new Email(), new Length(['max' => 255])],
            ],
            [
                ['type' => 'float'], [new Type('float')],
            ],
            [
                ['type' => 'iban'], [new Iban(), new Regex('/^[0-9A-Z]+$')],
            ],
            [
                ['type' => 'integer'], [new Type('integer')],
            ],
            [
                ['type' => 'ip'], [new Ip(['version' => 'all'])],
            ],
            [
                ['type' => 'json'], [],
            ],
            [
                ['type' => 'latitude', 'scale' => 4], [new Latitude(), new FloatScale(4)],
            ],
            [
                ['type' => 'latitude', 'scale' => null], [new Latitude(), new FloatScale(6)],
            ],
            [
                ['type' => 'locale'], [new Locale(['canonicalize' => true])],
            ],
            [
                ['type' => 'longitude', 'scale' => 4], [new Longitude(), new FloatScale(4)],
            ],
            [
                ['type' => 'longitude', 'scale' => null], [new Longitude(), new FloatScale(6)],
            ],
            [
                ['type' => 'money', 'scale' => null], [new Money(), new FloatScale(2)],
            ],
            [
                ['type' => 'money', 'scale' => 4], [new Money(), new FloatScale(4)],
            ],
            [
                ['type' => 'percent', 'scale' => null], [new Percent(), new FloatScale(2)],
            ],
            [
                ['type' => 'percent', 'scale' => 4], [new Percent(), new FloatScale(4)],
            ],
            [
                ['type' => 'phone'], [ new Phone()],
            ],
            [
                ['type' => 'phonelandline'], [ new PhoneLandline()],
            ],
            [
                ['type' => 'phonemobile'], [ new PhoneMobile()],
            ],
            [
                ['type' => 'postal'], [ new Postal([
                    'countryPropertyPath' => 'country'
                ]) ],
            ],
            [
                ['type' => 'smallint', 'options' => [ 'unsigned' => true]],
                [new Type('integer'), new GreaterThan(0), new LessThanOrEqual(pow(2, 16) - 1)],
            ],
            [
                ['type' => 'smallint', 'options' => [ 'unsigned' => false]],
                [new Type('integer'), new GreaterThanOrEqual(- pow(2, 15)), new LessThanOrEqual(pow(2, 15) - 1)],
            ],
            [
                ['type' => 'smallint'],
                [new Type('integer'), new GreaterThanOrEqual(- pow(2, 15)), new LessThanOrEqual(pow(2, 15) - 1)],
            ],
            [
                ['type' => 'string'], [ new Length(['max' => 255])],
            ],
            [
                ['type' => 'string', 'length' => 10], [new Length(['max' => 10])]
            ],
            [
                ['type' => 'text'], [new Length(['max' => 65535, 'charset' => '8bit'])],
            ],
            [
                ['type' => 'text', 'length' => 1000], [new Length(['max' => 1000, 'charset' => '8bit'])],
            ],
            [
                ['type' => 'timezone'], [new Timezone()]
            ],
            [
                ['type' => 'uuid'], [new Uuid()],
            ],
            [
                ['type' => 'uuid_binary_ordered_time'], [new Uuid()]
            ]
        ];
    }

    public function testGetConstraintsForNullableField()
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'nullable'),
            [new Country()]
        );
    }

    public function testGetConstraintsForNotNullableField()
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'notnullable'),
            [new NotNull(), new Country()]
        );
    }

    public function testGetConstraintsForEmbeddable()
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'embedded'),
            [new Valid()]
        );
    }

    public function testGetConstraintsForRelationNotOWningSide()
    {
        $this->assertEmpty($this->validator->getConstraints('class', 'notowning'));
    }

    public function testGetConstraintsForRelationToOne()
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOne'),
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationToOneNotNullable()
    {
        $this->assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOneNotNull'),
            [new Type(MyEntityParent::class), new NotNull()]
        );
    }

    public function testGetConstraintsForRelationToMany()
    {
        $constraints = $this->validator->getConstraints('class', 'owningToMany');
        $this->assertArrayContainsSameObjects(
            $constraints,
            [new All(['constraints' => [new Type(MyEntityParent::class)]])]
        );
        $this->assertArrayContainsSameObjects(
            $constraints[0]->constraints,
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationUnknown()
    {
        $this->expectException(\DomainException::class);

        $this->validator->getConstraints('class', 'owningUnknown');
    }

    public function testGetConstraintsForUnknownField()
    {
        $this->expectException(\LogicException::class);

        $this->validator->getConstraints('class', 'unknown');
    }

    public function providerInvalidValue(): array
    {
        return [];
    }

    public function providerValidValue(): array
    {
        return [];
    }

    private function getMockClassMetadata()
    {
        $metadata = new class {
            public $fieldMappings = [
                'nullable' => [
                    'type' => 'country',
                    'nullable' => true,
                ],
                'notnullable' => [
                    'type' => 'country',
                    'nullable' => false,
                ],
                'postal' => [
                    'type' => 'postal',
                    'nullable' => false,
                ]
            ];

            public $embeddedClasses = [
                'embedded' => [
                    'type' => 'bic'
                ],
            ];

            public $associationMappings = [
                'notowning' => [
                    'isOwningSide' => false,
                    'targetEntity' => MyEntityParent::class,
                    'type' => ClassMetadata::TO_ONE,
                ],
                'owningToOne' => [
                    'isOwningSide' => true,
                    'type' => ClassMetadata::TO_ONE,
                    'targetEntity' => MyEntityParent::class,
                ],
                'owningToOneNotNull' => [
                    'isOwningSide' => true,
                    'type' => ClassMetadata::TO_ONE,
                    'targetEntity' => MyEntityParent::class,
                    'joinColumns' => [['nullable' => false]],
                ],
                'owningToMany' => [
                    'isOwningSide' => true,
                    'type' => ClassMetadata::TO_MANY,
                    'targetEntity' => MyEntityParent::class,
                ],
                'owningUnknown' => [
                    'isOwningSide' => true,
                    'type' => 0,
                ],
            ];

            public function getReflectionProperties()
            {
                return get_object_vars(new MyEntity());
            }
        };

        return $metadata;
    }
}
