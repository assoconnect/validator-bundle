<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FrenchRnaValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        // Source : https://www.data.gouv.fr/fr/datasets/repertoire-national-des-associations/
        // the "jgmrt" is a specific letter for the DOM-TOM
        if ($this->isValidFrenchRna($value, true) ||
            $this->isValidFrenchRna($value, false)
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode(FrenchRna::INVALID_FORMAT_ERROR)
            ->addViolation();
    }

    public function getSupportedConstraint(): string
    {
        return FrenchRna::class;
    }

    private function isValidFrenchRna($value, bool $checkForCapitalLetters): bool
    {
        // The letters must be all uppercase or all lowercase but not a mix of both types
        if ($checkForCapitalLetters) {
            $firstCharacters           = 'W';
            $specificLettersFirstPart  = 'JGMRT';
            $specificLettersSecondPart = 'PS';
        } else {
            $firstCharacters           = 'w';
            $specificLettersFirstPart  = 'jgmrt';
            $specificLettersSecondPart = 'ps';
        }

        return (!(preg_match(
                '/(^' . $firstCharacters . '\d[\d' . $specificLettersFirstPart .
                ']\d{7}$)|(^\d[\d' . $specificLettersFirstPart . ']\d[' . $specificLettersSecondPart . ']\d{10}$)/',
                $value
            ) !== 1));
    }
}
