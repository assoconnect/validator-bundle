<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LuhnValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FrenchSirenValidator extends LuhnValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FrenchSiren) {
            throw new UnexpectedTypeException($constraint, FrenchSiren::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) || preg_match('/(^\d{9}$)/', $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(FrenchSiren::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        parent::validate($value, $constraint);
    }
}
