<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LuhnValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FrenchSiretValidator extends LuhnValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FrenchSiret) {
            throw new UnexpectedTypeException($constraint, FrenchSiret::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) || preg_match('/(^\d{14}$)/', $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(FrenchSiret::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        parent::validate($value, $constraint);
    }
}
