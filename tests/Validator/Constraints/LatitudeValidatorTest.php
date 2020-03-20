<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorWithKernelTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class LatitudeValidatorTest extends ConstraintValidatorWithKernelTestCase
{
    public function getContraint($options = []): Constraint
    {
        return new Latitude($options);
    }

    public function providerValidValue(): array
    {
        return [
            [null],
            [-90.0],
            [0],
            [90.0],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            // Value type
            ['', array(), [Type::INVALID_TYPE_ERROR]],
            // Default range
            [-91.0, array(), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [91.0, array(), [LessThanOrEqual::TOO_HIGH_ERROR]],
        ];
    }
}
