<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Test\Functional\App\Entity\MyEntityParent;
use AssoConnect\ValidatorBundle\Validator\Constraints\Entity;
use AssoConnect\ValidatorBundle\Validator\Constraints\EntityValidator;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\PhoneProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\FieldMapping;
use Doctrine\ORM\Mapping\ManyToManyOwningSideMapping;
use Doctrine\ORM\Mapping\ManyToOneAssociationMapping;
use Doctrine\ORM\Mapping\OneToManyAssociationMapping;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<EntityValidator>
 */
class EntityValidatorTest extends ConstraintValidatorTestCase
{
    protected EntityManagerInterface $em;

    /** @var ClassMetadata<MyEntityParent> */
    protected ClassMetadata $classMetadata;

    protected function setUp(): void
    {
        $this->classMetadata = $this->getMockClassMetadata();
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->em->method('getClassMetadata')->willReturn($this->classMetadata);

        parent::setUp();
    }

    protected function getConstraint(): Constraint
    {
        return new Entity();
    }

    public function createValidator(): ConstraintValidator
    {
        return new EntityValidator($this->em, [new PhoneProvider()]);
    }

    public function testGetConstraintsForTypeUnknown(): void
    {
        $fieldMapping = [
            'type' => 'fail',
            'fieldName' => 'some_name',
            'columnName' => 'some_name',
        ];

        $this->expectException(\DomainException::class);
        $this->validator->getConstraintsForType($fieldMapping);
    }

    public function testGetConstraintsForType(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraintsForType([
                'type' => 'phone',
                'fieldName' => 'some_name',
                'columnName' => 'some_name',
            ]),
            [new Phone()]
        );
    }

    public function testGetConstraintsForNullableField(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'nullable'),
            [new Phone()]
        );
    }

    public function testGetConstraintsForNotNullableField(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'notnullable'),
            [new NotNull(), new Phone()]
        );
    }

    public function testGetConstraintsForEmbeddable(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'embedded'),
            [new Valid()]
        );
    }

    public function testGetConstraintsForRelationNotOWningSide(): void
    {
        self::assertEmpty($this->validator->getConstraints('class', 'notowning'));
    }

    public function testGetConstraintsForRelationToOne(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOne'),
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationToOneNotNullable(): void
    {
        self::assertArrayContainsSameObjects(
            $this->validator->getConstraints('class', 'owningToOneNotNull'),
            [new Type(MyEntityParent::class), new NotNull()]
        );
    }

    public function testGetConstraintsForRelationToMany(): void
    {
        $constraints = $this->validator->getConstraints('class', 'owningToMany');
        self::assertArrayContainsSameObjects(
            $constraints,
            [new All(constraints: [new Type(MyEntityParent::class)])]
        );
        self::assertInstanceOf(All::class, $constraints[0]);
        self::assertIsArray($constraints[0]->constraints);
        self::assertArrayContainsSameObjects(
            $constraints[0]->constraints,
            [new Type(MyEntityParent::class)]
        );
    }

    public function testGetConstraintsForRelationUnknown(): void
    {
        $this->expectException(\DomainException::class);

        $this->validator->getConstraints('class', 'owningUnknown');
    }

    public function testGetConstraintsForUnknownField(): void
    {
        $this->expectException(\LogicException::class);

        $this->validator->getConstraints('class', 'unknown');
    }

    public function testValidateChecksEveryMappedField(): void
    {
        $entity = new class {
            public readonly ?string $nullable;

            public function __construct()
            {
                $this->nullable = '+33611223344';
            }
        };
        $this->classMetadata->reflFields = [
            'nullable' => new \ReflectionProperty($entity, 'nullable'),
        ];

        $this->expectValidateValueAt(0, 'nullable', '+33611223344', [new Phone()]);

        $this->validator->validate($entity, new Entity());
    }

    public function testGetConstraintsForNotNullableFieldWithOrm3MappingObject(): void
    {
        self::skipUnlessOrm3();

        $metadata = new ClassMetadata(MyEntityParent::class);
        $metadata->fieldMappings = [
            'notnullable' => FieldMapping::fromMappingArray([
                'type' => 'phone',
                'fieldName' => 'notnullable',
                'columnName' => 'notnullable',
                'nullable' => false,
            ]),
        ];

        self::assertArrayContainsSameObjects(
            $this->createValidatorForMetadata($metadata)->getConstraints('class', 'notnullable'),
            [new NotNull(), new Phone()]
        );
    }

    public function testGetConstraintsForRelationsWithOrm3MappingObjects(): void
    {
        self::skipUnlessOrm3();

        $metadata = new ClassMetadata(MyEntityParent::class);
        $metadata->associationMappings = [
            'notowning' => OneToManyAssociationMapping::fromMappingArray([
                'fieldName' => 'notowning',
                'sourceEntity' => MyEntityParent::class,
                'targetEntity' => MyEntityParent::class,
                'mappedBy' => 'parent',
            ]),
            'owningToOne' => ManyToOneAssociationMapping::fromMappingArray([
                'fieldName' => 'owningToOne',
                'sourceEntity' => MyEntityParent::class,
                'targetEntity' => MyEntityParent::class,
            ]),
            'owningToOneNotNull' => ManyToOneAssociationMapping::fromMappingArray([
                'fieldName' => 'owningToOneNotNull',
                'sourceEntity' => MyEntityParent::class,
                'targetEntity' => MyEntityParent::class,
                'joinColumns' => [['name' => 'parent_id', 'referencedColumnName' => 'id', 'nullable' => false]],
            ]),
            'owningToMany' => ManyToManyOwningSideMapping::fromMappingArray([
                'fieldName' => 'owningToMany',
                'sourceEntity' => MyEntityParent::class,
                'targetEntity' => MyEntityParent::class,
            ]),
        ];
        $validator = $this->createValidatorForMetadata($metadata);

        self::assertEmpty($validator->getConstraints('class', 'notowning'));
        self::assertArrayContainsSameObjects(
            $validator->getConstraints('class', 'owningToOne'),
            [new Type(MyEntityParent::class)]
        );
        self::assertArrayContainsSameObjects(
            $validator->getConstraints('class', 'owningToOneNotNull'),
            [new Type(MyEntityParent::class), new NotNull()]
        );
        self::assertArrayContainsSameObjects(
            $validator->getConstraints('class', 'owningToMany'),
            [new All(constraints: [new Type(MyEntityParent::class)])]
        );
    }

    private static function skipUnlessOrm3(): void
    {
        if (!class_exists(FieldMapping::class)) {
            self::markTestSkipped('Requires the doctrine/orm 3 mapping objects');
        }
    }

    /**
     * @param ClassMetadata<MyEntityParent> $metadata
     */
    private function createValidatorForMetadata(ClassMetadata $metadata): EntityValidator
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getClassMetadata')->willReturn($metadata);

        return new EntityValidator($em, [new PhoneProvider()]);
    }

    public static function providerInvalidValues(): iterable
    {
        return [];
    }

    public static function providerValidValues(): iterable
    {
        return [];
    }

    public function testValidValues(mixed $value = null): void
    {
        self::markTestSkipped('EntityValidator validates fields based on Doctrine metadata, not raw values');
    }

    public function testInvalidValues(
        mixed $value = null,
        string $code = '',
        string $message = '',
        ?array $parameters = null
    ): void {
        self::markTestSkipped('EntityValidator validates fields based on Doctrine metadata, not raw values');
    }

    /**
     * @return ClassMetadata<MyEntityParent>
     */
    private function getMockClassMetadata(): ClassMetadata
    {
        $metadata = new ClassMetadata(MyEntityParent::class);
        $metadata->fieldMappings = [
            'nullable' => [
                'type' => 'phone',
                'nullable' => true,
            ],
            'notnullable' => [
                'type' => 'phone',
                'nullable' => false,
            ],
        ];
        $metadata->embeddedClasses = [
            'embedded' => [
                'type' => 'bic',
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
