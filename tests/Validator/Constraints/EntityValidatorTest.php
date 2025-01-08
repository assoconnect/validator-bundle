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
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @psalm-import-type FieldMapping from ClassMetadataInfo
 * @extends ConstraintValidatorTestCase<EntityValidator>
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

        $this->validator->getConstraints('class', 'owningUnknown');
    }

    public function testGetConstraintsForUnknownField(): void
    {
        $this->expectException(\LogicException::class);

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
                'type' => ClassMetadataInfo::TO_ONE,
            ],
            'owningToOne' => [
                'isOwningSide' => true,
                'type' => ClassMetadataInfo::TO_ONE,
                'targetEntity' => MyEntityParent::class,
            ],
            'owningToOneNotNull' => [
                'isOwningSide' => true,
                'type' => ClassMetadataInfo::TO_ONE,
                'targetEntity' => MyEntityParent::class,
                'joinColumns' => [['nullable' => false]],
            ],
            'owningToMany' => [
                'isOwningSide' => true,
                'type' => ClassMetadataInfo::TO_MANY,
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
