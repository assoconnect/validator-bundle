<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\SpanishNif;
use AssoConnect\ValidatorBundle\Validator\Constraints\SpanishNifValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<SpanishNifValidator>
 */
class SpanishNifValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new SpanishNif();
    }

    public function createValidator(): ConstraintValidator
    {
        return new SpanishNifValidator();
    }

    public static function providerValidValues(): iterable
    {
        yield 'null value' => [null];
        yield 'empty string' => [''];
        yield 'valid NIF with digit suffix' => ['A12345678'];
        yield 'valid NIF with letter suffix' => ['B1234567C'];
        yield 'valid NIF all zeros' => ['Z00000000'];
        yield 'valid NIF mixed alphanumeric suffix' => ['H1234567A'];
    }

    public static function providerInvalidValues(): iterable
    {
        $expectedMessage = SpanishNif::MESSAGE;
        $code = SpanishNif::WRONG_FORMAT_ERROR;

        yield 'too short' => ['A1234567', $code, $expectedMessage];
        yield 'too long' => ['A1234567890', $code, $expectedMessage];
        yield 'starts with digit' => ['112345678', $code, $expectedMessage];
        yield 'starts with lowercase' => ['a12345678', $code, $expectedMessage];
        yield 'contains lowercase' => ['A1234567b', $code, $expectedMessage];
        yield 'contains special characters' => ['A1234-678', $code, $expectedMessage];
        yield 'contains spaces' => ['A1234 678', $code, $expectedMessage];
        yield 'single letter' => ['A', $code, $expectedMessage];
    }
}
