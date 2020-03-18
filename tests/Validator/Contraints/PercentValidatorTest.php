<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorWithKernelTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Percent;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class PercentValidatorTest extends ConstraintValidatorWithKernelTestCase
{
    public function getContraint($options = []): Constraint
    {
        return new Percent($options);
    }

    public function providerValidValue(): array
    {
        return [
            [null],
            [0.0],
            [0],
            [25.0],
            [100.0],
            [100],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            // Value type
            ['', array(), [Type::INVALID_TYPE_ERROR]],
            // Default range
            [-1.0, array(), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [101.0, array(), [LessThanOrEqual::TOO_HIGH_ERROR]],
            // Custom range
            [0.0, array('min' => 10), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [11.0, array('max' => 10), [LessThanOrEqual::TOO_HIGH_ERROR]],
        ];
    }
}
