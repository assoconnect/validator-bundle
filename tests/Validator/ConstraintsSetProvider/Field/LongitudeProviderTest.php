<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LongitudeType;
use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\LongitudeProvider;

class LongitudeProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new LongitudeProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            [
                'type' => 'longitude',
                'scale' => 4,
            ],
            [
                new Longitude(),
                new FloatScale(4),
            ],
        ];

        yield [
            [
                'type' => 'longitude',
                'scale' => LongitudeType::DEFAULT_SCALE - 1,
            ],
            [
                new Longitude(),
                new FloatScale(LongitudeType::DEFAULT_SCALE - 1),
            ],
        ];

        yield [
            [
                'type' => 'longitude',
                'scale' => null,
            ],
            [
                new Longitude(),
                new FloatScale(LongitudeType::DEFAULT_SCALE),
            ],
        ];
    }
}
