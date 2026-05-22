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
        yield 'type A with digit control' => ['A58818501']; // Wikipedia example
        yield 'type A with digit control (Adyen)' => ['A39000013']; // Adyen docs example
        yield 'type P with letter control' => ['P2813200B']; // Ayuntamiento de Madrid
    }

    public static function providerInvalidValues(): iterable
    {
        $code = SpanishNif::WRONG_FORMAT_ERROR;
        $message = SpanishNif::MESSAGE;

        yield 'too short' => ['A1234567', $code, $message];
        yield 'too long' => ['A1234567890', $code, $message];
        yield 'starts with digit' => ['112345678', $code, $message];
        yield 'starts with lowercase' => ['a12345678', $code, $message];
        yield 'invalid first letter' => ['Z12345678A', $code, $message];
        yield 'wrong control digit' => ['A58818502', $code, $message];
        yield 'wrong control letter' => ['P2813200Z', $code, $message];
        yield 'contains special character' => ['A1234-678', $code, $message];
        yield 'contains space' => ['A1234 678', $code, $message];
    }
}