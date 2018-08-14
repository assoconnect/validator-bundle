<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use App\Doctrine\DBAL\Types\EmailType as EmailType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator as _EmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

Class EmailValidator extends _EmailValidator
{

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
        // TODO : Utiliser une liste issue de Symfony ?
        $tlds = array_values(include(__DIR__ . '/../../vendor/umpirsky/tld-list/data/en/tld.php'));
        $domainName = explode('@', $value);
        $tld = explode('.', $domainName[1]);
        $tld = end($tld);
        if(in_array($tld, $tlds) === false) {
            $this->context->buildViolation($constraint->TLDMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_TLD_ERROR)
                ->addViolation();
            return;
        }
        // Symfony default checks
        // TODO : Utiliser le check de Symfony ?
        // parent::validate($value, $constraint);
    }

}