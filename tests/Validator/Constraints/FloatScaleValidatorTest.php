<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScaleValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class FloatScaleValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new FloatScale(['scale' => 2]);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new FloatScaleValidator();
    }

    public function providerValidValues(): iterable
    {
        yield [2.1];
        yield 'not a float value' => ['2.01'];
    }

    public function providerInvalidValues(): iterable
    {
        yield 'too precise' => [
            0.0001,
            FloatScale::TOO_PRECISE_ERROR,
            'The float precision is limited to {{ scale }} numbers.',
            [
                '{{ scale }}' => '2',
                '{{ value }}' => '0.0001',
            ],
        ];
    }
}
