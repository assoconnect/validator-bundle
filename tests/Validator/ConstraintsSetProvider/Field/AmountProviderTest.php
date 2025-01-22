<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\AmountAsBigintType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\AmountType;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\AmountProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\Mapping\FieldMapping;
use Money\Money;
use Symfony\Component\Validator\Constraints\Type;

class AmountProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new AmountProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => AmountType::NAME,
                    'fieldName' => AmountType::NAME,
                    'columnName' => AmountType::NAME,
                ]
            ),
            [new Type(Money::class)],
        ];

        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => AmountAsBigintType::NAME,
                    'fieldName' => AmountAsBigintType::NAME,
                    'columnName' => AmountAsBigintType::NAME,
                ]
            ),
            [new Type(Money::class)],
        ];
    }
}
