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
        yield 'valid number starting with 0' => ['0123456749'];
        yield 'valid number starting with 1' => ['1234567894'];
        yield 'valid number starting with 0 all zeros' => ['0000000097'];
        yield 'valid number starting with 1 all digits' => ['1999999943'];
    }

    public static function providerInvalidValues(): iterable
    {
        $formatMessage = BelgianEnterpriseNumber::MESSAGE;
        $formatCode = BelgianEnterpriseNumber::WRONG_FORMAT_ERROR;
        $checksumMessage = BelgianEnterpriseNumber::INVALID_CHECKSUM_MESSAGE;
        $checksumCode = BelgianEnterpriseNumber::INVALID_CHECKSUM_ERROR;

        yield 'too short' => ['012345678', $formatCode, $formatMessage];
        yield 'too long' => ['01234567890', $formatCode, $formatMessage];
        yield 'starts with 2' => ['2123456789', $formatCode, $formatMessage];
        yield 'starts with 9' => ['9123456789', $formatCode, $formatMessage];
        yield 'contains letters' => ['012345678A', $formatCode, $formatMessage];
        yield 'contains special characters' => ['01234-6789', $formatCode, $formatMessage];
        yield 'contains spaces' => ['0123 56789', $formatCode, $formatMessage];
        yield 'single digit' => ['0', $formatCode, $formatMessage];
        yield 'wrong checksum starting with 0' => ['0123456789', $checksumCode, $checksumMessage];
        yield 'wrong checksum starting with 1' => ['1234567890', $checksumCode, $checksumMessage];
        yield 'wrong checksum all zeros' => ['0000000000', $checksumCode, $checksumMessage];
    }
}
