<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LuhnValidator;

class FrenchSirenValidator extends LuhnValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (preg_match('/(^\d{9}$)/', $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(FrenchSiren::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        parent::validate($value, $constraint);
    }

    public function getSupportedConstraint(): string
    {
        return FrenchSiren::class;
    }
}
