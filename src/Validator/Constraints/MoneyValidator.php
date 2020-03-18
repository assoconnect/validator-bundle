<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;

class MoneyValidator extends ComposeValidator
{
    protected function getSupportedConstraint(): string
    {
        return Money::class;
    }

    protected function getConstraints($value, Constraint $constraint): array
    {
        if (is_float($value) or is_integer($value)) {
            return [
                new GreaterThanOrEqual($constraint->min),
                new LessThan($constraint->max),
            ];
        } else {
            return [
                new Type('float'),
            ];
        }
    }

    protected function isEmptyStringAccepted(): bool
    {
        return false;
    }
}
