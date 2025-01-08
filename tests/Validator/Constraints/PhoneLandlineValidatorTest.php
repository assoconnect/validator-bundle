<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<PhoneValidator>
 */
class PhoneLandlineValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new PhoneLandline();
    }

    public function createValidator(): ConstraintValidator
    {
        return new PhoneValidator();
    }

    public function providerValidValues(): iterable
    {
        yield [null];
        yield [''];
        yield ['+33123456789'];
    }

    public function providerInvalidValues(): iterable
    {
        yield [
            '+33623456789',
            Phone::INVALID_TYPE_ERROR,
            'The value {{ value }} is not a valid landline phone number.',
        ];
    }
}
