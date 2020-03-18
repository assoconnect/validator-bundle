<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Pdp\Manager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator as _EmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailValidator extends _EmailValidator
{
    private $manager;

    public function __construct(Manager $manager, string $defaultMode = Email::VALIDATION_MODE_LOOSE)
    {
        parent::__construct($defaultMode);
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($constraint instanceof Email === false) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Email');
        }
        if ($value === null || $value === '') {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }

        $domainName = explode('@', $value)[1];
        $rules = $this->manager->getRules();

        $domain = $rules->resolve($domainName);


        if ($domain->isKnown() === false) {
            $this->context->buildViolation($constraint->TLDMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_TLD_ERROR)
                ->addViolation();
            return;
        }

        parent::validate($value, $constraint);
    }
}
