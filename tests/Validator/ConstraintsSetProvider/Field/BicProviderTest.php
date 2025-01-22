<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\BooleanProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Type;

class BooleanProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new BooleanProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => Types::BOOLEAN,
                    'fieldName' => Types::BOOLEAN,
                    'columnName' => Types::BOOLEAN,
                ]
            ),
            [new Type('bool')],
        ];
    }
}
