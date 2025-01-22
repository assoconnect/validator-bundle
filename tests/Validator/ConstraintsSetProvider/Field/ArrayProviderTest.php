<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\ArrayProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Type;

class ArrayProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new ArrayProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => Types::ARRAY,
                    'fieldName' => Types::ARRAY,
                    'columnName' => Types::ARRAY,
                ]
            ),
            [new Type('array')],
        ];
        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => Types::SIMPLE_ARRAY,
                    'fieldName' => Types::SIMPLE_ARRAY,
                    'columnName' => Types::SIMPLE_ARRAY,
                ]
            ),
            [new Type('array')],
        ];
    }
}
