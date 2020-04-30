<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Egulias\EmailValidator\Validation\DNSCheckValidation;
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
        if (!$constraint instanceof Email) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Email');
        }
        if ($value === null || $value === '') {
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }

        $rules = $this->manager->getRules();

        $domainName = explode('@', $value)[1];
        $domain = $rules->resolve($domainName);

        if (!$domain->isKnown()) {
            $this->buildTldError($value, $constraint);
            return;
        }

        $validator = new \Egulias\EmailValidator\EmailValidator();
        if (!$validator->isValid($value, new DNSCheckValidation())) {
            $this->buildTldError($value, $constraint);
            return;
        }

        parent::validate($value, $constraint);
    }

    private function buildTldError($value, Constraint $constraint)
    {
        $this->context->buildViolation($constraint->TLDMessage)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode(Email::INVALID_TLD_ERROR)
            ->addViolation();
    }
}
