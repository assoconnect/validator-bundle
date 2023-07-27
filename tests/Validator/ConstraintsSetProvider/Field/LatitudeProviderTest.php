<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LatitudeType;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\LatitudeProvider;

class LatitudeProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new LatitudeProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            [
                'type' => 'latitude',
                'scale' => LatitudeType::DEFAULT_SCALE - 1,
            ],
            [
                new Latitude(),
                new FloatScale(LatitudeType::DEFAULT_SCALE - 1),
            ],
        ];

        yield [
            [
                'type' => 'latitude',
                'scale' => null,
            ],
            [
                new Latitude(),
                new FloatScale(LatitudeType::DEFAULT_SCALE),
            ],
        ];

        yield [
            ['type' => 'latitude'],
            [
                new Latitude(),
                new FloatScale(LatitudeType::DEFAULT_SCALE),
            ],
        ];
    }
}
