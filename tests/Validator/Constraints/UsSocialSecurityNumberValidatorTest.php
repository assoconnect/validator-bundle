<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumberValidator;
use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\UsSocialSecurityNumberProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

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
        yield 'empty US SSN' => [''];
        yield 'null US SSN' => [null];
        yield 'valid US SSN' => ['123-456-789'];
        yield 'an other valid US SSN' => ['078-05-1120'];
    }

    public function providerInvalidValues(): iterable
    {
        $invalidMessage = 'The value {{ value }} is not a valid US Social Security Number.';

        yield 'first part is not 3 digits' => [
            '08-05-1120',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage
        ];
        yield 'second part is not 2 digits' => [
            '078-5-1120',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage
        ];
        yield 'third part is not 4 digits' => [
            '078-05-120',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage
        ];
        yield 'first part is 000' => ['000-12-3456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part is 666' => ['666-12-3456', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'first part starts with 9' => [
            '900-12-3456',
            UsSocialSecurityNumber::INVALID_FORMAT_ERROR,
            $invalidMessage,
        ];
        yield 'second part is 00' => ['123-00-6789', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
        yield 'third part is 0000' => ['123-45-0000', UsSocialSecurityNumber::INVALID_FORMAT_ERROR, $invalidMessage];
    }
}
