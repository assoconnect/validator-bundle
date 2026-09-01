<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LuhnValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates the 11-digit numeric Codice Fiscale of Italian legal persons, which
 * shares its format and Luhn check digit with the Partita IVA. The 16-character
 * alphanumeric codice fiscale of natural persons is rejected: this constraint
 * targets organization identifiers only.
 */
class ItalianFiscalCodeValidator extends LuhnValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ItalianFiscalCode) {
            throw new UnexpectedTypeException($constraint, ItalianFiscalCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) || preg_match('/(^\d{11}$)/', $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(ItalianFiscalCode::WRONG_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        parent::validate($value, $constraint);
    }
}
