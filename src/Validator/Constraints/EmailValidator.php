<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Pdp\Manager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator as _EmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The 4 steps for validation are:
 * 1. PHP filter_var() function
 * 2. Does the domain use a valid public suffix?
 * 3. Is there at least one MX server for the domain?
 * 4. Default Symfony validation
 */
class EmailValidator extends _EmailValidator
{
    private Manager $manager;

    public function __construct(Manager $manager, string $defaultMode = Email::VALIDATION_MODE_LOOSE)
    {
        parent::__construct($defaultMode);
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Email) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Email');
        }
        if ($value === null || $value === '') {
            return;
        }

        // 1. PHP filter_var() function
        // First one because it is a quick one
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }


        // 2. Does the domain use a valid public suffix?
        // See https://en.wikipedia.org/wiki/Public_Suffix_List
        $rules = $this->manager->getRules();
        // The lib does not accept Public Suffix domain so We add a sub. prefix in case the domain is a Public Suffix
        // For instance notaires.fr is a public suffix while is it valid to use @notaires.fr
        // https://github.com/jeremykendall/php-domain-parser/blob/develop/src/Domain.php#L166
        // A CouldNotResolvePublicSuffix would be raised
        $domainName = explode('@', $value)[1];
        $domain = $rules->resolve('sub.' . $domainName);

        if (!$domain->isKnown()) {
            $this->context->buildViolation($constraint->tldMessage)
                ->setParameter('{{ domain }}', $this->formatValue($domainName))
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_TLD_ERROR)
                ->addViolation();
            return;
        }

        // 3. DNS check
        $validator = new \Egulias\EmailValidator\EmailValidator();
        if ($constraint->checkDNS && !$validator->isValid($value, new DNSCheckValidation())) {
            $this->context->buildViolation($constraint->dnsMessage)
                ->setParameter('{{ domain }}', $this->formatValue($domainName))
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_DNS_ERROR)
                ->addViolation();
            return;
        }

        parent::validate($value, $constraint);
    }
}
