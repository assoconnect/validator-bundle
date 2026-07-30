<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\EuropeanVatNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\EuropeanVatNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<EuropeanVatNumberValidator>
 */
class EuropeanVatNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new EuropeanVatNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new EuropeanVatNumberValidator();
    }

    public static function providerValidValues(): iterable
    {
        yield 'null value' => [null];
        yield 'empty string' => [''];
        yield 'Belgian number starting with 0' => ['BE0123456789'];
        yield 'Belgian number starting with 1' => ['BE1123456789'];
        yield 'German number' => ['DE123456789'];
        yield 'French number' => ['FR12345678901'];
        yield 'Greek number with the official EL prefix' => ['EL123456789'];
        yield 'Croatian number' => ['HR12345678901'];
        yield 'Dutch number' => ['NL123456789B12'];
        yield 'Cypriot number' => ['CY10259033P'];
        yield 'Irish number new style with one letter' => ['IE6433435F'];
        yield 'Irish number new style with two letters' => ['IE3628739UA'];
        yield 'Irish number old style' => ['IE8Z49289F'];
        yield 'lowercase input is normalized' => ['be0123456789'];
        yield 'inner spaces are normalized' => ['BE 0123 456 789'];
    }

    public static function providerInvalidValues(): iterable
    {
        $code = EuropeanVatNumber::INVALID_FORMAT_ERROR;
        $message = EuropeanVatNumber::MESSAGE;

        yield 'GB prefix (not an EU member)' => ['GB123456789', $code, $message];
        yield 'unknown prefix' => ['XX123456789', $code, $message];
        yield 'single character' => ['B', $code, $message];
        yield 'wrong body length' => ['BE123', $code, $message];
        yield 'Cypriot number without its trailing letter' => ['CY123456789', $code, $message];
        yield 'trailing newline' => ["DE123456789\n", $code, $message];
        yield 'Greek number with the GR country code' => ['GR123456789', $code, $message];
    }
}
