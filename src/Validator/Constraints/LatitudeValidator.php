<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class LatitudeValidator extends ComposeValidator
{
    public const REGEX = '/^-?[0-9]+\.?[0-9]*$/';

    public function getSupportedConstraint(): string
    {
        return Latitude::class;
    }

    public function getConstraints($value, Constraint $constraint): array
    {
        if (is_string($value)) {
            if (preg_match(self::REGEX, $value) === 0) {
                return [new Regex(self::REGEX)];
            }

            return [
                new GreaterThanOrEqual(-90),
                new LessThanOrEqual(90),
            ];
        }

        return [new Type('string')];
    }

    public function isEmptyStringAccepted(): bool
    {
        return false;
    }
}
