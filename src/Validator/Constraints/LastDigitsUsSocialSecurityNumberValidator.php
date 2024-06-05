<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LastDigitsUsSocialSecurityNumberValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof LastDigitsUsSocialSecurityNumber) {
            throw new UnexpectedTypeException($constraint, LastDigitsUsSocialSecurityNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) || preg_match('/^(?!0000)\d{4}$/', $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(UsSocialSecurityNumber::INVALID_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
