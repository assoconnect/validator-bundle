<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class PhoneValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new Phone();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new PhoneValidator();
    }

    public function providerValidValues(): iterable
    {
        yield [null];
        yield [''];
        yield ['+33123456789'];
        yield ['+33623456789'];
    }

    public function providerInvalidValues(): iterable
    {
        yield [
            '0123456789',
            Phone::NOT_INTL_FORMAT_ERROR,
            'The value {{ value }} is not formatted as an international phone number.'
        ];

        yield [
            '+ABC',
            Phone::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid phone number.'
        ];

        yield [
            '+3310000000',
            Phone::PHONE_NUMBER_NOT_EXIST,
            'The phone number {{ value }} does not exist.'
        ];
    }
}
