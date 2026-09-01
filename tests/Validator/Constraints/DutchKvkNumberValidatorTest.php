<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\DutchKvkNumber;
use AssoConnect\ValidatorBundle\Validator\Constraints\DutchKvkNumberValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends ConstraintValidatorTestCase<DutchKvkNumberValidator>
 */
class DutchKvkNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new DutchKvkNumber();
    }

    public function createValidator(): ConstraintValidator
    {
        return new DutchKvkNumberValidator();
    }

    public static function providerValidValues(): iterable
    {
        yield 'null value' => [null];
        yield 'empty string' => [''];
        // Adyen documentation example
        yield 'valid KVK number' => ['34179503'];
        yield 'valid KVK number with leading zero' => ['01234567'];
        yield 'valid KVK number all zeros' => ['00000000'];
    }

    public static function providerInvalidValues(): iterable
    {
        $formatMessage = DutchKvkNumber::MESSAGE;
        $formatCode = DutchKvkNumber::WRONG_FORMAT_ERROR;

        yield 'too short' => ['3417950', $formatCode, $formatMessage];
        yield 'too long' => ['341795031', $formatCode, $formatMessage];
        yield 'contains letters' => ['3417950A', $formatCode, $formatMessage];
        yield 'contains internal space' => ['3417 503', $formatCode, $formatMessage];
        yield 'contains special characters' => ['3417-503', $formatCode, $formatMessage];
        yield 'leading plus sign' => ['+4179503', $formatCode, $formatMessage];
        yield 'single digit' => ['3', $formatCode, $formatMessage];
    }
}
