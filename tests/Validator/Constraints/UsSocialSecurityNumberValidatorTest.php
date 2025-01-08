<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<UsSocialSecurityNumberValidator>
 */
class UsSocialSecurityNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new UsSocialSecurityNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new UsSocialSecurityNumberValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty US SSN' => [''];
        yield 'null US SSN' => [null];
        yield 'valid US SSN' => ['123456789'];
        yield 'an other valid US SSN' => ['078051120'];
    }

    public function providerInvalidValues(): iterable
    {
        $invalidMessage = 'The value {{ value }} is not a valid US Social Security Number.';

        yield 'SSN is less than 9 digits' => [
            '08051120',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'SSN is more than 9 digits' => [
            '0780511209',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'first part is 000' => ['000123456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part is 666' => ['666123456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part starts with 9' => [
            '900123456',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'second part is 00' => ['123006789', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'third part is 0000' => ['123450000', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
    }
}
