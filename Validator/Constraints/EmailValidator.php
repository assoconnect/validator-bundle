<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use LayerShifter\TLDDatabase\Store;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator as _EmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

Class EmailValidator extends _EmailValidator
{

    /**
     * @var Store
     */
    private $store;

    public function __construct(Store $store, string $defaultMode = Email::VALIDATION_MODE_LOOSE)
    {
        parent::__construct($defaultMode);
        $this->store = $store;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        // Check instance type
        if($constraint instanceof Email === false) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Email');
        }
        // Null is valid
        if($value === null OR $value === '') {
            return;
        }
        // Format check
        if(filter_var($value, FILTER_VALIDATE_EMAIL) === false){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }
        // TLD check
        $domainName = explode('@', $value);
        $tld = explode('.', $domainName[1]);
        $tld = end($tld);
        if($this->store->isExists($tld) === false) {
            $this->context->buildViolation($constraint->TLDMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_TLD_ERROR)
                ->addViolation();
            return;
        }
        // Symfony default checks
        parent::validate($value, $constraint);
    }

}