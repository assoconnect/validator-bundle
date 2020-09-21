<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class PercentValidator extends ComposeValidator
{
    public function getSupportedConstraint(): string
    {
        return Percent::class;
    }

    public function getConstraints($value, Constraint $constraint): array
    {
        if (is_float($value) || is_integer($value)) {
            return [
                new GreaterThanOrEqual($constraint->min),
                new LessThanOrEqual($constraint->max),
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

    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof \AssoConnect\PHPPercent\Percent) {
            $value = $value->toInteger();
        }
        return parent::validate($value, $constraint);
    }
}
