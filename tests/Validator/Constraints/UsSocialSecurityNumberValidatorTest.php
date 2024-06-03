<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;

class UsSocialSecurityNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new UsSocialSecurityNumberProvider();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new UsSocialSecurityNumberValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty SSN' => [''];
        yield 'null SSN' => [null];
        yield 'valid SSN' => ['123-456-789'];
        yield 'an other valid SSN' => ['078-05-1120'];
    }

    public function providerInvalidValues(): iterable
    {
        $invalidMessage = 'The value {{ value }} is not a valid US Social Security Number.';

        yield 'first part is 000' => ['000123456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part is 666' => ['666123456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part starts with 9' => [
            '900123456',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'second part is 00' => ['123-00-6789', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'valid 123-45-0000' => ['123-45-0000', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
    }
}
