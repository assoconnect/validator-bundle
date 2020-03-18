<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FloatScaleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($constraint instanceof FloatScale === false) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\FloatScale');
        }

        if (is_float($value) === false) {
            return;
        }

        if ($value !== round($value, $constraint->scale)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setParameter('{{ scale }}', $constraint->scale)
                ->setCode(FloatScale::TOO_PRECISE_ERROR)
                ->addViolation();
            return;
        }
    }
}
