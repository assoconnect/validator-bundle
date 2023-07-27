<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\AmountProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
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
            ['type' => 'amount'],
            [new Type(Money::class)],
        ];

        yield [
            ['type' => 'amountAsBigint'],
            [new Type(Money::class)],
        ];
    }
}
