<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UsSocialSecurityNumberValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UsSocialSecurityNumber) {
            throw new UnexpectedTypeException($constraint, UsSocialSecurityNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (
            !is_string($value) ||
            preg_match('/^(?!000|666|9\d\d)\d{3}-(?!00)\d{2}-(?!0000)\d{4}$/', $value) !== 1
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(UsSocialSecurityNumber::INVALID_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
