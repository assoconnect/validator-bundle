<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\BicType;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\BicProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Regex;

class BicProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new BicProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            FieldMapping::fromMappingArray(
                [
                    'type' => BicType::NAME,
                    'fieldName' => BicType::NAME,
                    'columnName' => BicType::NAME,
                ]
            ),
            [
                new Bic(),
                new Regex('/^[0-9A-Z]+$/'),
            ],
        ];
    }
}
