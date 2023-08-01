<?php

declare(strict_types=1);

namespace Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSirenValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class FrenchSirenValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new FrenchSiren();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new FrenchSirenValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty value' => [''];
        yield 'null value' => [null];
        yield 'valid SIREN' => ['732829320'];
    }

    public function providerInvalidValues(): iterable
    {
        yield 'wrong type' => [
            42,
            FrenchSiren::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIREN number.',
        ];

        yield 'SIREN with alphabetic character' => [
            '123456A789',
            FrenchSiren::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIREN number.',
        ];

        yield 'Too short SIREN' => [
            '73282320',
            FrenchSiren::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIREN number.',
        ];

        yield 'Wrong SIREN' => [
            '732829321',
            FrenchSiren::CHECKSUM_FAILED_ERROR,
            'The value {{ value }} is not a valid SIREN number.',
        ];
    }
}
