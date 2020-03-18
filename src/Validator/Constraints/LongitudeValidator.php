<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class LongitudeValidator extends ComposeValidator
{
    protected function getSupportedConstraint(): string
    {
        return Longitude::class;
    }

    protected function getConstraints($value, Constraint $constraint): array
    {
        if (is_float($value) or is_integer($value)) {
            return [
                new GreaterThanOrEqual(-180),
                new LessThanOrEqual(180),
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
