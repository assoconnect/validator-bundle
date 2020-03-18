<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($constraint instanceof Phone === false) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Phone');
        }

        if ($value === '' or $value === null) {
            return;
        }

        if (substr($value, 0, 1) !== '+') {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(PhoneLandline::INVALID_FORMAT_ERROR)
                ->addViolation();
            return;
        }

        $phone = $value;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneObject = $phoneUtil->parse($phone);
            // Phone number is possible
            if ($phoneUtil->isPossibleNumber($phoneObject)) {
                // Valid phone number
                $phoneNumberType = $phoneUtil->getNumberType($phoneObject);
                if (in_array($phoneNumberType, $constraint->validTypes)) {
                    return;
                } elseif (in_array($phoneNumberType, $constraint->invalidTypes)) {
                    $this->context->buildViolation($constraint->invalidTypeMessage)
                        ->setParameter('{{ value }}', $this->formatValue($value))
                        ->setCode(Phone::INVALID_TYPE_ERROR)
                        ->addViolation();
                } else {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', $this->formatValue($value))
                        ->setCode(Phone::INVALID_FORMAT_ERROR)
                        ->addViolation();
                }
            } else {
                $this->context->buildViolation($constraint->inexistantMessage)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Phone::PHONE_NUMBER_NOT_EXIST)
                    ->addViolation();
            }
        } catch (NumberParseException $exception) {
            switch ($exception->getErrorType()) {
                case NumberParseException::NOT_A_NUMBER:
                    $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Phone::INVALID_FORMAT_ERROR)
                    ->addViolation();
                    break;
                case NumberParseException::TOO_SHORT_AFTER_IDD:
                case NumberParseException::TOO_SHORT_NSN:
                    $this->context->buildViolation($constraint->tooShortMessage)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Phone::LENGTH_MIN_ERROR)
                    ->addViolation();
                    break;
                case NumberParseException::TOO_LONG:
                    $this->context->buildViolation($constraint->tooLongMessage)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Phone::LENGTH_MAX_ERROR)
                    ->addViolation();
                    break;
                default:
                    throw $exception;
            }
        }
    }
}
