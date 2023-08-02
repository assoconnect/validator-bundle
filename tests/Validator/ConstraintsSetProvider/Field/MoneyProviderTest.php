<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\MoneyProvider;

class MoneyProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new MoneyProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            [
                'type' => 'money',
                'scale' => null,
            ],
            [
                new Money(),
                new FloatScale(2),
            ],
        ];

        yield [
            [
                'type' => 'money',
                'scale' => 4,
            ],
            [
                new Money(),
                new FloatScale(4),
            ],
        ];
    }
}
