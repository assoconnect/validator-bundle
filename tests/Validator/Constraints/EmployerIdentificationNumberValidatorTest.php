<?php

declare(strict_types=1);

namespace Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmployerIdentificationNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmployerIdentificationNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @extends ConstraintValidatorTestCase<EmployerIdentificationNumberValidator>
 */
class EmployerIdentificationNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new EmployerIdentificationNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new EmployerIdentificationNumberValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty value' => [''];
        yield 'null value' => [null];
        yield 'valid EIN (0[1-6])' => ['03-0604760'];
        yield 'valid EIN (1[0-6])' => ['12-3456789'];
        yield 'valid EIN (2[0-7])' => ['26-0604760'];
        yield 'valid EIN ([35]\d)' => ['39-0604760'];
        yield 'valid EIN ([468][0-8])' => ['88-0604760'];
        yield 'valid EIN (7[1-7])' => ['77-0604760'];
        yield 'valid EIN (9[0-58-9])' => ['95-0604760'];
    }

    public function testWrongTypeValue(): void
    {
        $value = 42;
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate($value, $this->getConstraint());
    }

    public function providerInvalidValues(): iterable
    {
        yield 'EIN in wrong format (no -)' => [
            '123456789',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (more than seven digits after the -)' => [
            '03-06047609',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (less than seven digits after the -)' => [
            '03-060476',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (starting with 00)' => [
            '00-0604760',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (2[0-7])' => [
            '28-0604760',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format ([468][0-8])' => [
            '49-0604760',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (7[1-7])' => [
            '78-0604760',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
        yield 'EIN in wrong format (9[0-58-9])' => [
            '96-0604760',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.',
        ];
    }
}
