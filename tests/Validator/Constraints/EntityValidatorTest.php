<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntityParent;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use AssoConnect\ValidatorBundle\Validator\Constraints\EntityValidator;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchRna;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money as MoneyConstraint;
use AssoConnect\ValidatorBundle\Validator\Constraints\Percent;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Money\Currency;
use Money\Money;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Timezone;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Ulid;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Currency as CurrencyConstraint;

/**
 * @psalm-import-type FieldMapping from ClassMetadataInfo
 */
class EntityValidatorTest extends ConstraintValidatorTestCase
{
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->em->method('getClassMetadata')->willReturn($this->getMockClassMetadata());

        parent::setUp();
    }

    protected function getConstraint(): Constraint
    {
        return new Entity();
    }

    public function createValidator(): EntityValidator
    {
        return new EntityValidator($this->em);
    }

    public function testGetConstraintsForTypeUnknown(): void
    {
        $fieldMapping = [
            'type' => 'fail'
        ];

        $this->expectException(\DomainException::class);
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        $this->validator->getConstraintsForType($fieldMapping);
    }

    /**
     * @param FieldMapping $fieldMapping
     * @param array<Constraint> $constraints
     * @dataProvider getConstraintsForTypeProvider
     */
    public function testGetConstraintsForType(array $fieldMapping, array $constraints): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraintsForType($fieldMapping),
            $constraints
        );
    }

    /**
     * @return iterable<mixed>
     */
    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'amount'],
            [new Type(Money::class)]
        ];

        yield [
            ['type' => 'amountAsBigint'],
            [new Type(Money::class)]
        ];

        yield [
            ['type' => 'array'],
            [new Type('array')],
        ];

        yield [
            ['type' => 'bic'],
            [new Bic(), new Regex('/^[0-9A-Z]+$/')],
        ];

        yield [
            ['type' => 'bigint', 'options' => [ 'unsigned' => true]],
            [new Type('integer'), new GreaterThanOrEqual(0), new LessThanOrEqual(pow(2, 64) - 1)],
        ];

        yield [
            ['type' => 'bigint', 'options' => [ 'unsigned' => false]],
            [new Type('integer'), new GreaterThanOrEqual(- pow(2, 63)), new LessThanOrEqual(pow(2, 63) - 1)],
        ];

        yield [
            ['type' => 'bigint'],
            [new Type('integer'), new GreaterThanOrEqual(- pow(2, 63)), new LessThanOrEqual(pow(2, 63) - 1)],
        ];

        yield [
            ['type' => 'boolean'],
            [new Type('bool')],
        ];

        yield [
            ['type' => 'country'],
            [new Country()],
        ];

        yield [
            ['type' => 'currency'],
            [new CurrencyConstraint(), new Type(Currency::class)],
        ];

        yield [
            ['type' => 'date'],
            [new Type(\DateTime::class)],
        ];

        yield [
            ['type' => 'datetime'],
            [new Type(\DateTime::class)],
        ];

        yield [
            ['type' => 'datetimetz'],
            [new Type(\DateTime::class)],
        ];

        yield [
            ['type' => 'datetimeutc'],
            [new Type(\DateTime::class)],
        ];

        yield [
            ['type' => 'datetimezone'],
            [ new Type(\DateTimeZone::class)],
        ];

        yield [
            ['type' => 'date_absolute'],
            [ new Type(AbsoluteDate::class)],
        ];

        yield [
            ['type' => 'decimal', 'precision' => 4, 'scale' => 2],
            [
                new Type('float'),
                new GreaterThan(- pow(10, 4 - 2)),
                new LessThan(pow(10, 4 - 2)),
                new FloatScale(2)
            ]
        ];

        yield [
            ['type' => 'email', 'length' => 10],
            [new Email(), new Length(['max' => 10])],
        ];

        yield [
            ['type' => 'email'],
            [new Email(), new Length(['max' => 255])],
        ];

        yield [
            ['type' => 'float'],
            [new Type('float')],
        ];

        yield [
            ['type' => 'frenchRna'],
            [new FrenchRna()]
        ];

        yield [
            ['type' => 'frenchSiren'],
            [new FrenchSiren()]
        ];

        yield [
            ['type' => 'iban'],
            [new Iban(), new Regex('/^[0-9A-Z]+$')],
        ];

        yield [
            ['type' => 'integer'],
            [new Type('integer')],
        ];

        yield [
            ['type' => 'ip'],
            [new Ip(['version' => 'all'])],
        ];

        yield [
            ['type' => 'json'],
            [],
        ];

        yield [
            ['type' => 'latitude', 'scale' => 4],
            [new Latitude(), new FloatScale(4)],
        ];

        yield [
            ['type' => 'latitude', 'scale' => null],
            [new Latitude(), new FloatScale(6)],
        ];

        yield [
            ['type' => 'locale'],
            [new Locale(['canonicalize' => true])],
        ];

        yield [
            ['type' => 'longitude', 'scale' => 4],
            [new Longitude(), new FloatScale(4)],
        ];

        yield [
            ['type' => 'longitude', 'scale' => null],
            [new Longitude(), new FloatScale(6)],
        ];

        yield [
            ['type' => 'money', 'scale' => null],
            [new MoneyConstraint(), new FloatScale(2)],
        ];

        yield [
            ['type' => 'money', 'scale' => 4],
            [new MoneyConstraint(), new FloatScale(4)],
        ];

        yield [
            ['type' => 'percent', 'scale' => null],
            [new Type(Percent::class), new Percent()],
        ];

        yield [
            ['type' => 'phone'],
            [new Phone()],
        ];

        yield [
            ['type' => 'phonelandline'],
            [new PhoneLandline()],
        ];

        yield [
            ['type' => 'phonemobile'],
            [new PhoneMobile()],
        ];

        yield [
            ['type' => 'postal'], [],
        ];

        yield [
            ['type' => 'smallint', 'options' => [ 'unsigned' => true]],
            [new Type('integer'), new GreaterThan(0), new LessThanOrEqual(pow(2, 16) - 1)],
        ];

        yield [
            ['type' => 'smallint', 'options' => [ 'unsigned' => false]],
            [new Type('integer'), new GreaterThanOrEqual(- pow(2, 15)), new LessThanOrEqual(pow(2, 15) - 1)],
        ];

        yield [
            ['type' => 'smallint'],
            [new Type('integer'), new GreaterThanOrEqual(- pow(2, 15)), new LessThanOrEqual(pow(2, 15) - 1)],
        ];

        yield [
            ['type' => 'string'],
            [new Length(['max' => 255])],
        ];

        yield [
            ['type' => 'string', 'length' => 10],
            [new Length(['max' => 10])]
        ];

        yield [
            ['type' => 'string', 'nullable' => true],
            [new Length(['max' => 255]), new NotBlank(['allowNull' => true])]
        ];

        yield [
            ['type' => 'text'],
            [new Length(['max' => 65535, 'charset' => '8bit'])],
        ];

        yield [
            ['type' => 'text', 'length' => 1000],
            [new Length(['max' => 1000, 'charset' => '8bit'])],
        ];

        yield [
            ['type' => 'timezone'],
            [new Timezone()]
        ];

        yield [
            ['type' => 'ulid'],
            [new Ulid()],
        ];

        yield [
            ['type' => 'uuid'],
            [new Uuid()],
        ];

        yield [
            ['type' => 'uuid_binary_ordered_time'],
            [new Uuid()]
        ];
    }

    public function testGetConstraintsForNullableField(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'nullable'),
            [new Country()]
        );
    }

    public function testGetConstraintsForNotNullableField(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'notnullable'),
            [new NotNull(), new Country()]
        );
    }

    public function testGetConstraintsForEmbeddable(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'embedded'),
            [new Valid()]
        );
    }

    public function testGetConstraintsForRelationNotOWningSide(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertEmpty($this->validator->getConstraints('class', 'notowning'));
    }

    public function testGetConstraintsForRelationToOne(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOne'),
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationToOneNotNullable(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOneNotNull'),
            [new Type(MyEntityParent::class), new NotNull()]
        );
    }

    public function testGetConstraintsForRelationToMany(): void
    {
        self::assertInstanceOf(EntityValidator::class, $this->validator);
        $constraints = $this->validator->getConstraints('class', 'owningToMany');
        self::assertArrayContainsSameObjects(
            $constraints,
            [new All(['constraints' => [new Type(MyEntityParent::class)]])]
        );
        self::assertInstanceOf(All::class, $constraints[0]);
        self::assertArrayContainsSameObjects(
            $constraints[0]->constraints,
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationUnknown(): void
    {
        $this->expectException(\DomainException::class);

        self::assertInstanceOf(EntityValidator::class, $this->validator);
        $this->validator->getConstraints('class', 'owningUnknown');
    }

    public function testGetConstraintsForUnknownField(): void
    {
        $this->expectException(\LogicException::class);

        self::assertInstanceOf(EntityValidator::class, $this->validator);
        $this->validator->getConstraints('class', 'unknown');
    }

    public function providerInvalidValues(): iterable
    {
        return [];
    }

    public function providerValidValues(): iterable
    {
        return [];
    }

    private function getMockClassMetadata(): \stdClass
    {
        $metadata = new \stdClass();
        $metadata->fieldMappings = [
            'nullable' => [
                'type' => 'country',
                'nullable' => true,
            ],
            'notnullable' => [
                'type' => 'country',
                'nullable' => false,
            ],
        ];
        $metadata->embeddedClasses = [
            'embedded' => [
                'type' => 'bic'
            ],
        ];
        $metadata->associationMappings = [
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

        return $metadata;
    }
}
