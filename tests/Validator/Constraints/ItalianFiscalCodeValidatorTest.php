<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\ItalianFiscalCode;
use AssoConnect\ValidatorBundle\Validator\Constraints\ItalianFiscalCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<ItalianFiscalCodeValidator>
 */
class ItalianFiscalCodeValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new ItalianFiscalCode();
    }

    public function createValidator(): ConstraintValidator
    {
        return new ItalianFiscalCodeValidator();
    }

    public static function providerValidValues(): iterable
    {
        yield 'null value' => [null];
        yield 'empty string' => [''];
        // Adyen documentation example
        yield 'valid fiscal code' => ['00470400011'];
        // Public Partita IVA example (same format and check digit as the fiscal code)
        yield 'valid fiscal code of a company' => ['00159560366'];
        // Public fiscal code of a non-commercial entity
        yield 'valid fiscal code of a non-commercial entity' => ['80078750587'];
    }

    public static function providerInvalidValues(): iterable
    {
        $formatMessage = ItalianFiscalCode::MESSAGE;
        $formatCode = ItalianFiscalCode::WRONG_FORMAT_ERROR;

        yield 'wrong type' => [42, $formatCode, $formatMessage];
        yield 'too short' => ['0047040001', $formatCode, $formatMessage];
        yield 'too long' => ['004704000110', $formatCode, $formatMessage];
        yield 'contains letters' => ['0047040001A', $formatCode, $formatMessage];
        yield 'contains spaces' => ['00470 00011', $formatCode, $formatMessage];
        yield '16-character personal codice fiscale' => ['RSSMRA85T10A562S', $formatCode, $formatMessage];
        yield 'wrong checksum' => ['00470400012', ItalianFiscalCode::CHECKSUM_FAILED_ERROR, $formatMessage];
    }
}
