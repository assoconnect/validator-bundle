<?php

declare(strict_types=1);

namespace Validator\Constraints;

use App\ThirdParty\ApiPlatform\Exception\NotSupportedGetItemException;
use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmployerIdentificationNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmployerIdentificationNumberValidator;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmployerIdentificationNumberValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint(): Constraint
    {
        return new EmployerIdentificationNumber();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new EmployerIdentificationNumberValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty value' => [''];
        yield 'null value' => [null];
        yield 'valid EIN' => ['12-3456789'];
    }

    public function testWrongTypeValue(): void
    {
        $value = 42;
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate($value, $this->getConstraint());
    }

    public function providerInvalidValues(): iterable
    {
        yield 'EIN in wrong format' => [
            '123456789',
            EmployerIdentificationNumber::WRONG_FORMAT_ERROR,
            'The value {{ value }} is not a valid employer identification number.'
        ];
    }
}
