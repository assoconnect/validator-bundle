<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Dto\ValidatorAndConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqualValidator;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanValidator;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\TypeValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyValidator extends ComposeValidator
{
    public function getSupportedConstraint(): string
    {
        return Money::class;
    }

    public function getValidatorsAndConstraints(mixed $value, Constraint $constraint): array
    {
        if (!$constraint instanceof Money) {
            throw new UnexpectedTypeException($constraint, Money::class);
        }

        if (is_float($value) || is_integer($value)) {
            return [
                new ValidatorAndConstraint(new GreaterThanOrEqualValidator(), new GreaterThanOrEqual($constraint->min)),
                new ValidatorAndConstraint(new LessThanValidator(), new LessThanOrEqual($constraint->max)),
            ];
        }

        return [new ValidatorAndConstraint(new TypeValidator(), new Type('float'))];
    }

    protected function isEmptyStringAccepted(): bool
    {
        return false;
    }
}
