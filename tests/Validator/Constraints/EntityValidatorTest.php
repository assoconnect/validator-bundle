<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEmbeddable;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntity;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntityParent;
use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use AssoConnect\ValidatorBundle\Validator\Constraints\EntityValidator;
use AssoConnect\PHPDate\AbsoluteDate;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use AssoConnect\ValidatorBundle\Validator\Constraints\Percent;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Money\Currency;
use PHPUnit\Framework\TestCase;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Currency as CurrencyConstraint;

class EntityValidatorTest extends TestCase
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

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
        $this->validator = new EntityValidator($this->entityManager);

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
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraintsForType($fieldMapping), $constraints
            )
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
                ['type' => 'postal'], [],
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
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraints('class', 'nullable'),
                [new Country()]
            )
        );
    }

    public function testGetConstraintsForNotNullableField()
    {
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraints('class', 'notnullable'),
                [new NotNull(), new Country()]
            )
        );
    }

    public function testGetConstraintsForEmbeddable()
    {
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraints('class', 'embedded'),
                [new Valid()]
            )
        );
    }

    public function testGetConstraintsForRelationNotOWningSide()
    {
        $this->assertEmpty($this->validator->getConstraints('class', 'notowning'));
    }

    public function testGetConstraintsForRelationToOne()
    {
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraints('class', 'owningToOne'),
                [new Type(MyEntityParent::class)]
            )
        );
    }

    public function testGetConstraintsForRelationToOneNotNullable()
    {
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $this->validator->getConstraints('class', 'owningToOneNotNull'),
                [new Type(MyEntityParent::class), new NotNull()]
            )
        );
    }

    public function testGetConstraintsForRelationToMany()
    {
        $constraints = $this->validator->getConstraints('class', 'owningToMany');
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $constraints,
                [new All(['constraints' => [new Type(MyEntityParent::class)]])]
            )
        );
        $this->assertTrue(
            $this->assertArrayContainsSameObjects(
                $constraints[0]->constraints,
                [new Type(MyEntityParent::class)]
            )
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

    public function testValidate()
    {
        $metadata = $this->createMock(ClassMetadata::class);
        $metadata->method('getReflectionProperties')->willReturn(['nullable' => '']);
        $this->entityManager->method('getClassMetadata')->will(
            $this->onConsecutiveCalls($metadata, $this->getMockClassMetadata())
        );
    }

    public function TestValidValues()
    {
        $entity = new MyEntity();

        $entity->id = 123;
        $entity->bic = 'SOGEFRPP';
        $entity->bigint = -123456789;
        $entity->bigintUnsigned = 123456789;
        $entity->boolean = true;
        $entity->country = 'FR';
        $entity->currency = new Currency('EUR');
        $entity->date = new \DateTime();
        $entity->datetime = new \DateTime();
        $entity->decimal = 99.999;
        $entity->email = 'foo@bar.com';
        $entity->float = 1.2;
        $entity->latitude = 2.3;
        $entity->locale = 'fr_FR';
        $entity->longitude = 3.4;
        $entity->iban = 'CH9300762011623852957';
        $entity->integer = 123;
        $entity->ip = '172.16.254.1';
        $entity->json = array();
        $entity->money = 4.5;
        $entity->notNullable = 'oui';
        $entity->percent = 5.6;
        $entity->phone = '+33123456789';
        $entity->phonelandline = '+33123456789';
        $entity->phonemobile = '+33623456789';
        $entity->smallint = -12345;
        $entity->smallintUnsigned = 12345;
        $entity->string = 'hello';
        $entity->text = 'world';
        $entity->timezone = 'Europe/Paris';
        $entity->uuid = '863c9c0f-59db-4ac7-9fd2-787c070b037c';
        $entity->uuid_binary_ordered_time = '6381fbe0-e651-46f5-b171-3f25518bd8e9';
        $entity->absoluteDate = new AbsoluteDate('2020-01-01');

        $entity->parentNullable = null;
        $entity->parentNotNullable = new MyEntityParent();
        $entity->parents = [new MyEntityParent()];

        $entity->embeddable = new MyEmbeddable(true);

        $errors = $this->validator->validate($entity, new Entity());
        foreach ($errors as $error) {
            var_dump($error->getPropertyPath() . ': ' . $error->getMessage());
        }
        $this->assertCount(0, $errors);
    }

    public function TestInvalidValues()
    {
        $entity = new MyEntity();
        $codes = [];

        $codes['id'] = [NotNull::IS_NULL_ERROR];

        $entity->bic = 'SOGEFRPPA';
        $codes['bic'] = [Bic::INVALID_LENGTH_ERROR];

        $entity->bigint = pow(2, 64);
        $codes['bigint'] = [Type::INVALID_TYPE_ERROR, LessThanOrEqual::TOO_HIGH_ERROR];

        $entity->bigintUnsigned = -12;
        $codes['bigintUnsigned'] = [GreaterThanOrEqual::TOO_LOW_ERROR];

        $entity->boolean = 1;
        $codes['boolean'] = [Type::INVALID_TYPE_ERROR];

        $entity->country = 'Hello';
        $codes['country'] = [Country::NO_SUCH_COUNTRY_ERROR];

        $entity->currency = 'foo';
        $codes['currency'] = [CurrencyConstraint::NO_SUCH_CURRENCY_ERROR];

        $entity->date = 'hello';
        $codes['date'] = [Type::INVALID_TYPE_ERROR];

        $entity->datetime = 'world';
        $codes['datetime'] = [Type::INVALID_TYPE_ERROR];

        $entity->decimal = 100.0;
        $codes['decimal'] = [LessThan::TOO_HIGH_ERROR];

        $entity->email = 'foo@bar.comcom';
        $codes['email'] = [Email::INVALID_TLD_ERROR];

        $entity->float = 'a';
        $codes['float'] = [Type::INVALID_TYPE_ERROR];

        $entity->iban = 'CH9300762011623852958';
        $codes['iban'] = [Iban::CHECKSUM_FAILED_ERROR];

        $entity->integer = 'abc';
        $codes['integer'] = [Type::INVALID_TYPE_ERROR];

        $entity->ip = 'bar';
        $codes['ip'] = [Ip::INVALID_IP_ERROR];

        $entity->json = array();
        // TODO: implement JSON validation?
        $entity->latitude = 91.0;
        $codes['latitude'] = [LessThanOrEqual::TOO_HIGH_ERROR];

        $entity->locale = 'foo';
        $codes['locale'] = [Locale::NO_SUCH_LOCALE_ERROR];

        $entity->longitude = 181.0;
        $codes['longitude'] = [LessThanOrEqual::TOO_HIGH_ERROR];

        $entity->money = -1;
        $codes['money'] = [GreaterThanOrEqual::TOO_LOW_ERROR];

        $entity->notNullable = null;
        $codes['notNullable'] = [NotNull::IS_NULL_ERROR];

        $entity->percent = -1;
        $codes['percent'] = [GreaterThanOrEqual::TOO_LOW_ERROR];

        $entity->phone = '+331234567890';
        $codes['phone'] = [Phone::PHONE_NUMBER_NOT_EXIST];

        $entity->phonelandline = '+33623456789';
        $codes['phonelandline'] = [Phone::INVALID_TYPE_ERROR];

        $entity->phonemobile = '+33123456789';
        $codes['phonemobile'] = [Phone::INVALID_TYPE_ERROR];

        $entity->smallint = pow(2, 16);
        $codes['smallint'] = [LessThanOrEqual::TOO_HIGH_ERROR];

        $entity->smallintUnsigned = -12;
        $codes['smallintUnsigned'] = [GreaterThan::TOO_LOW_ERROR];

        $entity->string = str_repeat('a', 11);
        $codes['string'] = [Length::TOO_LONG_ERROR];

        $entity->text = str_repeat('💩', 3);
        $codes['text'] = [Length::TOO_LONG_ERROR];

        $entity->timezone = 'foo';
        $codes['timezone'] = [Timezone::TIMEZONE_IDENTIFIER_ERROR];

        $entity->uuid = 'foo';
        $codes['uuid'] = [Uuid::INVALID_CHARACTERS_ERROR];

        $entity->uuid_binary_ordered_time = 'bar';
        $codes['uuid_binary_ordered_time'] = [Uuid::INVALID_CHARACTERS_ERROR];

        $entity->parentNullable = new MyEntity();
        $codes['parentNullable'] = [Type::INVALID_TYPE_ERROR];

        $entity->parentNotNullable = null;
        $codes['parentNotNullable'] = [NotNull::IS_NULL_ERROR];

        $entity->parents = [new MyEmbeddable('hello')];
        $codes['parents[0]'] = [Type::INVALID_TYPE_ERROR];

        $entity->embeddable = new MyEmbeddable('hello');
        $codes['embeddable.bool'] = [Type::INVALID_TYPE_ERROR];

        $entity->absoluteDate = 'invalid absolute date';
        $codes['absoluteDate'] = [Type::INVALID_TYPE_ERROR];

        $errors = $this->validator->validate($entity, new Entity());
        $errorsPerPath = [];
        foreach ($errors as $error) {
            $errorsPerPath[$error->getPropertyPath()][] = $error->getCode();
        }
        ksort($codes);
        ksort($errorsPerPath);
        $this->assertSame($codes, $errorsPerPath);
    }

    private function assertArrayContainsSameObjects(array $array1, array $array2)
    {
        if (count($array1) != count($array2)) {
            return false;
        }

        foreach($array1 as $key => $element) {
            if (get_class($element) !== get_class($array2[$key])) {
                return false;
            }
        }
        return true;
    }

    private function getMockClassMetadata()
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
