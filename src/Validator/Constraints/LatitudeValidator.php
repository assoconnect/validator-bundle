<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Dto\ValidatorAndConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqualValidator;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqualValidator;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\TypeValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LatitudeValidator extends ComposeValidator
{
    public const REGEX = '/^-?[0-9]+\.?[0-9]*$/';

    public function getSupportedConstraint(): string
    {
        return Latitude::class;
    }

    protected function isEmptyStringAccepted(): bool
    {
        return false;
    }

    public function getValidatorsAndConstraints(mixed $value, Constraint $constraint): array
    {
        if (!$constraint instanceof Latitude) {
            throw new UnexpectedTypeException($constraint, Latitude::class);
        }

        if (is_string($value)) {
            if (preg_match(self::REGEX, $value) !== 1) {
                return [new ValidatorAndConstraint(new RegexValidator(), new Regex(self::REGEX))];
            }

            return [
                new ValidatorAndConstraint(new GreaterThanOrEqualValidator(), new GreaterThanOrEqual(-90)),
                new ValidatorAndConstraint(new LessThanOrEqualValidator(), new LessThanOrEqual(90)),
            ];
        }

        return [new ValidatorAndConstraint(new TypeValidator(), new Type('string'))];
    }
}
