<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SpanishNifValidator extends ConstraintValidator
{
    // Valid first letters for a legal entity NIF
    private const string VALID_FIRST_LETTERS = 'ABCDEFGHJNPQRSUVW';

    // First letters requiring a letter as control character
    private const array LETTER_CONTROL = ['P', 'Q', 'R', 'S', 'W', 'N'];

    // First letters requiring a digit as control character
    private const array DIGIT_CONTROL = ['A', 'B', 'E', 'H'];

    // Mapping from control digit to control letter: J=0, A=1, B=2 ... I=9
    private const string CONTROL_LETTERS = 'JABCDEFGHI';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof SpanishNif) {
            throw new UnexpectedTypeException($constraint, SpanishNif::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->isValid($value)) {
            $this->context->buildViolation($constraint::MESSAGE)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode($constraint::WRONG_FORMAT_ERROR)
                ->addViolation();
        }
    }

    private function isValid(string $value): bool
    {
        // Strict format: known first letter + 7 digits + 1 control character
        if (
            1 !== preg_match('/^([' . self::VALID_FIRST_LETTERS . '])(\d{7})([0-9A-Z])$/', $value, $matches)
        ) {
            return false;
        }

        [, $firstLetter, $digits, $control] = $matches;

        $expectedDigit = $this->computeControlDigit($digits);
        $expectedLetter = self::CONTROL_LETTERS[$expectedDigit];

        if (in_array($firstLetter, self::LETTER_CONTROL, true)) {
            return $control === $expectedLetter;
        }

        if (in_array($firstLetter, self::DIGIT_CONTROL, true)) {
            return $control === (string)$expectedDigit;
        }

        // Other first letters accept either a digit or a letter
        return $control === (string)$expectedDigit || $control === $expectedLetter;
    }

    private function computeControlDigit(string $digits): int
    {
        // Sum digits at even positions (0-based indexes 1, 3, 5)
        $evenSum = (int)$digits[1] + (int)$digits[3] + (int)$digits[5];

        // For each odd position (0-based indexes 0, 2, 4, 6):
        // multiply by 2, then add the resulting digits together
        $oddSum = 0;
        foreach ([0, 2, 4, 6] as $i) {
            $val = (int)$digits[$i] * 2;
            $oddSum += $val >= 10 ? intdiv($val, 10) + ($val % 10) : $val;
        }

        $total = $evenSum + $oddSum;
        $units = $total % 10;

        return $units !== 0 ? 10 - $units : 0;
    }
}