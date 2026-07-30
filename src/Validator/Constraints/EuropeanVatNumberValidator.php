<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Format-only validation of intra-EU VAT numbers: two-letter VAT prefix (EU-27,
 * with the official EL prefix for Greece) followed by the country-specific body.
 * No checksum is verified, and presence stays the caller's decision.
 */
class EuropeanVatNumberValidator extends ConstraintValidator
{
    private const array PATTERNS = [
        'AT' => 'U[0-9]{8}',
        'BE' => '[01][0-9]{9}',
        'BG' => '[0-9]{9,10}',
        'CY' => '[0-9]{8}[A-Z]',
        'CZ' => '[0-9]{8,10}',
        'DE' => '[0-9]{9}',
        'DK' => '[0-9]{8}',
        'EE' => '[0-9]{9}',
        'EL' => '[0-9]{9}',
        'ES' => '[0-9A-Z][0-9]{7}[0-9A-Z]',
        'FI' => '[0-9]{8}',
        'FR' => '[0-9A-Z]{2}[0-9]{9}',
        'HR' => '[0-9]{11}',
        'HU' => '[0-9]{8}',
        'IE' => '([0-9]{7}[A-Z]{1,2}|[0-9][A-Z+*][0-9]{5}[A-Z])',
        'IT' => '[0-9]{11}',
        'LT' => '([0-9]{9}|[0-9]{12})',
        'LU' => '[0-9]{8}',
        'LV' => '[0-9]{11}',
        'MT' => '[0-9]{8}',
        'NL' => '[0-9]{9}B[0-9]{2}',
        'PL' => '[0-9]{10}',
        'PT' => '[0-9]{9}',
        'RO' => '[0-9]{2,10}',
        'SE' => '[0-9]{12}',
        'SI' => '[0-9]{8}',
        'SK' => '[0-9]{10}',
    ];

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EuropeanVatNumber) {
            throw new UnexpectedTypeException($constraint, EuropeanVatNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->hasValidFormat(strtoupper(str_replace(' ', '', $value)))) {
            return;
        }

        $this->context->buildViolation($constraint::MESSAGE)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode($constraint::INVALID_FORMAT_ERROR)
            ->addViolation();
    }

    private function hasValidFormat(string $normalized): bool
    {
        if (strlen($normalized) < 2) {
            return false;
        }

        $pattern = self::PATTERNS[substr($normalized, 0, 2)] ?? null;

        if (null === $pattern) {
            return false;
        }

        // \z (not $) so a trailing newline never slips through the format check.
        return 1 === preg_match('#^(?:' . $pattern . ')\z#', substr($normalized, 2));
    }
}
