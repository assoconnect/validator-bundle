<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScaleValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FloatScaleValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint(): Constraint
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
            ]
        ];
    }
}
