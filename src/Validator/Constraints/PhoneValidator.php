<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Phone) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Phone');
        }

        if ($value === '' || $value === null) {
            return;
        }

        if (substr($value, 0, 1) !== '+') {
            $this->buildViolation($value, $constraint->notIntlFormatMessage, Phone::NOT_INTL_FORMAT_ERROR);
            return;
        }

        $phone = $value;
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneObject = $phoneUtil->parse($phone);
            if ($phoneUtil->isPossibleNumber($phoneObject)) {
                $phoneNumberType = $phoneUtil->getNumberType($phoneObject);
                if (in_array($phoneNumberType, $constraint->getValidTypes(), true)) {
                    return;
                }
                $this->buildViolation($value, $constraint->wrongTypeMessage, Phone::INVALID_TYPE_ERROR);
                return;
            }

            $this->buildViolation($value, $constraint->inexistantMessage, Phone::PHONE_NUMBER_NOT_EXIST);
        } catch (NumberParseException $exception) {
            switch ($exception->getErrorType()) {
                case NumberParseException::NOT_A_NUMBER:
                    $this->buildViolation($value, $constraint->message, Phone::INVALID_FORMAT_ERROR);
                    break;
                case NumberParseException::TOO_SHORT_AFTER_IDD:
                case NumberParseException::TOO_SHORT_NSN:
                    $this->buildViolation($value, $constraint->tooShortMessage, Phone::LENGTH_MIN_ERROR);
                    break;
                case NumberParseException::TOO_LONG:
                    $this->buildViolation($value, $constraint->tooLongMessage, Phone::LENGTH_MAX_ERROR);
                    break;
                default:
                    throw $exception;
            }
        }
    }

    private function buildViolation(string $value, string $message, string $code): void
    {
        $this->context->buildViolation($message)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode($code)
            ->addViolation();
    }
}
