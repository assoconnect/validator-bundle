<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\IntegerProvider;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class IntegerProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new IntegerProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            [
                'type' => 'smallint',
                'options' => ['unsigned' => true],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(0),
                new LessThanOrEqual(65535),
            ],
        ];

        yield [
            [
                'type' => 'smallint',
                'options' => ['unsigned' => false],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(- 32768),
                new LessThanOrEqual(32767),
            ],
        ];

        yield [
            ['type' => 'smallint'],
            [
                new Type('integer'),
                new GreaterThanOrEqual(- pow(2, 15)),
                new LessThanOrEqual(pow(2, 15) - 1),
            ],
        ];

        yield [
            [
                'type' => 'integer',
                'options' => ['unsigned' => true],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(0),
                new LessThanOrEqual(4294967295),
            ],
        ];

        yield [
            [
                'type' => 'integer',
                'options' => ['unsigned' => false],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(-2147483648),
                new LessThanOrEqual(2147483647),
            ],
        ];

        yield [
            ['type' => 'integer'],
            [
                new Type('integer'),
                new GreaterThanOrEqual(-2147483648),
                new LessThanOrEqual(2147483647),
            ],
        ];

        yield [
            [
                'type' => 'bigint',
                'options' => ['unsigned' => true],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(0),
                new LessThanOrEqual(pow(2, 64) - 1),
            ],
        ];

        yield [
            [
                'type' => 'bigint',
                'options' => ['unsigned' => false],
            ],
            [
                new Type('integer'),
                new GreaterThanOrEqual(- pow(2, 63)),
                new LessThanOrEqual(pow(2, 63) - 1),
            ],
        ];

        yield [
            ['type' => 'bigint'],
            [
                new Type('integer'),
                new GreaterThanOrEqual(- pow(2, 63)),
                new LessThanOrEqual(pow(2, 63) - 1),
            ],
        ];
    }
}
