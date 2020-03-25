<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;

class MoneyValidator extends ComposeValidator
{
    public function getSupportedConstraint(): string
    {
        return Money::class;
    }

    public function getConstraints($value, Constraint $constraint): array
    {
        if (is_float($value) || is_integer($value)) {
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

    public function isEmptyStringAccepted(): bool
    {
        return false;
    }
}
