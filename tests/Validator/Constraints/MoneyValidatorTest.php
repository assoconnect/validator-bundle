<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorWithKernelTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;

class MoneyValidatorTest extends ConstraintValidatorWithKernelTestCase
{
    public function getContraint($options = []): Constraint
    {
        return new Money($options);
    }

    public function providerValidValue(): array
    {
        return [
            [null],
            [0.0],
            [0],
            [100.0],
            [Money::MAX - 0.1],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            // Value type
            ['', array(), [Type::INVALID_TYPE_ERROR]],
            // Default range
            [-1.0, array(), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [Money::MAX + 1, array(), [LessThan::TOO_HIGH_ERROR]],
            // Custom range
            [0.0, array('min' => 10), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [11.0, array('max' => 10), [LessThan::TOO_HIGH_ERROR]],
        ];
    }
}
