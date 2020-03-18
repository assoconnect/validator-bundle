<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorWithKernelTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class LongitudeValidatorTest extends ConstraintValidatorWithKernelTestCase
{
    public function getContraint($options = []): Constraint
    {
        return new Longitude($options);
    }

    public function providerValidValue(): array
    {
        return [
            [null],
            [-180.0],
            [0],
            [180.0],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            // Value type
            ['', array(), [Type::INVALID_TYPE_ERROR]],
            // Default range
            [-181.0, array(), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [181.0, array(), [LessThanOrEqual::TOO_HIGH_ERROR]],
        ];
    }
}
