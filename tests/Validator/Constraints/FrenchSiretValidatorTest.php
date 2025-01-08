<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiret;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiretValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<FrenchSiretValidator>
 */
class FrenchSiretValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new FrenchSiret();
    }

    public function createValidator(): ConstraintValidator
    {
        return new FrenchSiretValidator();
    }

    public function providerValidValues(): iterable
    {
        yield 'empty value' => [''];
        yield 'null value' => [null];
        yield 'valid SIRET' => ['53077557600040'];
    }

    public function providerInvalidValues(): iterable
    {
        yield 'wrong type' => [
            42,
            FrenchSiret::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIRET number.',
        ];

        yield 'SIRET with alphabetic character' => [
            '5307755760O040',
            FrenchSiret::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIRET number.',
        ];

        yield 'Too short SIRET' => [
            '5307755760004',
            FrenchSiret::INVALID_FORMAT_ERROR,
            'The value {{ value }} is not a valid SIRET number.',
        ];

        yield 'Wrong SIRET' => [
            '53077557603040',
            FrenchSiret::CHECKSUM_FAILED_ERROR,
            'The value {{ value }} is not a valid SIRET number.',
        ];
    }
}
