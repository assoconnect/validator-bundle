<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BelgianEnterpriseNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof BelgianEnterpriseNumber) {
            throw new UnexpectedTypeException($constraint, BelgianEnterpriseNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!ctype_digit($value) || strlen($value) !== 10 || !in_array($value[0], ['0', '1'], true)) {
            $this->context->buildViolation($constraint::MESSAGE)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode($constraint::WRONG_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
