<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Format-only validation of the Dutch KVK number (Kamer van Koophandel nummer),
 * the registration number of organizations in the Dutch trade register: exactly
 * 8 digits. KVK numbers carry no public check digit, so no checksum is verified,
 * and presence stays the caller's decision.
 */
class DutchKvkNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DutchKvkNumber) {
            throw new UnexpectedTypeException($constraint, DutchKvkNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (1 !== preg_match('/^\d{8}$/', $value)) {
            $this->context->buildViolation($constraint::MESSAGE)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode($constraint::WRONG_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
