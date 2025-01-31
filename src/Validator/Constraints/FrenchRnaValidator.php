<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FrenchRnaValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FrenchRna) {
            throw new UnexpectedTypeException($constraint, FrenchRna::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // Source : https://www.data.gouv.fr/fr/datasets/repertoire-national-des-associations/
        // the "ABGMCRSTJ" is a specific letter for the DOM-TOM and Corse
        if (
            !is_string($value) ||
            preg_match('/(^W\d[\dABGMCRSTJ]\d{7}$)|(^\d[\dABGMRT]\d[PS]((02[BA])|(\d{3}))\d{7}$)/', $value) !== 1
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(FrenchRna::INVALID_FORMAT_ERROR)
                ->addViolation();
        }
    }
}
