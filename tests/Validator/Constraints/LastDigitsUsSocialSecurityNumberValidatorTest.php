<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\LastDigitsUsSocialSecurityNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\LastDigitsUsSocialSecurityNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<LastDigitsUsSocialSecurityNumberValidator>
 */
class LastDigitsUsSocialSecurityNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new LastDigitsUsSocialSecurityNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new LastDigitsUsSocialSecurityNumberValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty US SSN' => [''];
        yield 'null US SSN' => [null];
        yield 'valid US SSN' => ['0001'];
        yield 'an other valid US SSN: 1120' => ['1120'];
        yield 'an other valid US SSN: 9999' => ['9999'];
    }

    public function providerInvalidValues(): iterable
    {
        $invalidMessage = 'The value {{ value }} is not a valid set of ' .
            'last four digits of a US Social Security Number.';

        yield 'Last four digits of US SSN is less than 4 digits' => [
            '112',
            LastDigitsUsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'Last four digits of US SSN is more than 5 digits' => [
            '12095',
            LastDigitsUsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'Last four digits of US SSN part is 0000' => [
            '0000',
            LastDigitsUsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
    }
}
