<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorWithKernelTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use Symfony\Component\Validator\Constraint;

class FloatScaleValidator extends ConstraintValidatorWithKernelTestCase
{
    public function getContraint($options = []): Constraint
    {
        return new FloatScale($options);
    }

    public function providerValidValue(): array
    {
        return [
            [null, 0],
            ['', 0],
            [1.1, 1],
            [1.01, 2],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            [1.001, 2, [FloatScale::TOO_PRECISE_ERROR]],
        ];
    }
}
