<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Pdp\Domain;
use Pdp\Storage\PublicSuffixListClient;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email as _Email;
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
    public const PUBLIC_SUFFIX_LIST_URI = 'https://publicsuffix.org/list/public_suffix_list.dat';
    private PublicSuffixListClient $publicSuffixListClient;

    public function __construct(
        PublicSuffixListClient $publicSuffixListClient,
        string $defaultMode = _Email::VALIDATION_MODE_LOOSE
    ) {
        parent::__construct($defaultMode);
        $this->publicSuffixListClient = $publicSuffixListClient;
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
                ->setCode(_Email::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }

        // 2. Does the domain use a valid public suffix?
        // See https://en.wikipedia.org/wiki/Public_Suffix_List
        $publicSuffixList = $this->publicSuffixListClient->get(self::PUBLIC_SUFFIX_LIST_URI);
        $domainName = explode('@', $value)[1];
        // If the domain is actually a public suffix (like @notaires.fr) then there is no suffix
        // So we add the "sub." prefix to ensure the method ->suffix() will always return a non-empty dto
        $result = $publicSuffixList->resolve(Domain::fromIDNA2008('sub.' . $domainName));
        if (!$result->suffix()->isKnown()) {
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
