<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\BelgianEnterpriseNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\BelgianEnterpriseNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<BelgianEnterpriseNumberValidator>
 */
class BelgianEnterpriseNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new BelgianEnterpriseNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new BelgianEnterpriseNumberValidator();
    }

    public static function providerValidValues(): iterable
    {
        yield 'null value' => [null];
        yield 'empty string' => [''];
        yield 'valid number starting with 0' => ['0123456789'];
        yield 'valid number starting with 1' => ['1234567890'];
        yield 'valid number starting with 0 all zeros' => ['0000000000'];
        yield 'valid number starting with 1 all digits' => ['1999999999'];
    }

    public static function providerInvalidValues(): iterable
    {
        $expectedMessage = 'The Belgian Enterprise Number {{ value }} is not valid.';
        $code = BelgianEnterpriseNumber::WRONG_FORMAT_ERROR;

        yield 'too short' => ['012345678', $code, $expectedMessage];
        yield 'too long' => ['01234567890', $code, $expectedMessage];
        yield 'starts with 2' => ['2123456789', $code, $expectedMessage];
        yield 'starts with 9' => ['9123456789', $code, $expectedMessage];
        yield 'contains letters' => ['012345678A', $code, $expectedMessage];
        yield 'contains special characters' => ['01234-6789', $code, $expectedMessage];
        yield 'contains spaces' => ['0123 56789', $code, $expectedMessage];
        yield 'single digit' => ['0', $code, $expectedMessage];
    }
}
