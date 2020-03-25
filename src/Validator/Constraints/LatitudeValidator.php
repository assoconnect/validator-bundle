<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class LatitudeValidator extends ComposeValidator
{
    public function getSupportedConstraint(): string
    {
        return Latitude::class;
    }

    public function getConstraints($value, Constraint $constraint): array
    {
        if (is_float($value) || is_integer($value)) {
            return [
                new GreaterThanOrEqual(-90),
                new LessThanOrEqual(90),
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
