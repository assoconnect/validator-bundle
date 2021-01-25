<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class LongitudeValidator extends ComposeValidator
{
    public const REGEX = '/^-?[0-9]+\.?[0-9]*$/';

    public function getSupportedConstraint(): string
    {
        return Longitude::class;
    }

    public function getConstraints($value, Constraint $constraint): array
    {
        if (is_string($value)) {
            if (preg_match(self::REGEX, $value) === 0) {
                return [new Regex(self::REGEX)];
            }

            return [
                new GreaterThanOrEqual(-180),
                new LessThanOrEqual(180),
            ];
        }

        return [new Type('string')];
    }

    public function isEmptyStringAccepted(): bool
    {
        return false;
    }
}
