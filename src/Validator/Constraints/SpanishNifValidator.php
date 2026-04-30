<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SpanishNifValidator extends ConstraintValidator
{
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

        if (!preg_match('/^[A-Z][A-Z0-9]{8}$/', $value)) {
            $this->context->buildViolation($constraint::MESSAGE)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode($constraint::WRONG_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
