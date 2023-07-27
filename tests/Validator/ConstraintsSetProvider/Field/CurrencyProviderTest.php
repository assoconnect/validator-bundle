<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Test\FieldConstraintsSetProviderTestCase;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\CurrencyProvider;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Money\Currency as CurrencyObject;
use Symfony\Component\Validator\Constraints\Currency as CurrencyConstraint;
use Symfony\Component\Validator\Constraints\Type;

class CurrencyProviderTest extends FieldConstraintsSetProviderTestCase
{
    protected function getFactory(): FieldConstraintsSetProviderInterface
    {
        return new CurrencyProvider();
    }

    public function getConstraintsForTypeProvider(): iterable
    {
        yield [
            ['type' => 'currency'],
            [
                new CurrencyConstraint(),
                new Type(CurrencyObject::class),
            ],
        ];
    }
}
