<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use libphonenumber\PhoneNumberType;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class Phone extends Constraint
{
    public const INVALID_FORMAT_ERROR = '24b57af8-7a39-4612-9239-4a98b72546f5';
    public const NOT_INTL_FORMAT_ERROR = '46f1aa4c-91e2-4a47-a422-7d2bec84c764';
    public const INVALID_TYPE_ERROR = 'e4b85033-1205-44e2-b6b4-e6a8310a22df';
    public const LENGTH_MAX_ERROR = '4e5a9535-133a-4ee5-a0ae-22e519bd3f94';
    public const LENGTH_MIN_ERROR = 'd8a6f526-e6e0-4ac3-9608-0f9936a3773c';
    public const PHONE_NUMBER_NOT_EXIST = 'f32ef12d-cefa-42d9-97f0-520d90276bf0';

    /**
     * @return array<int>
     */
    public function getValidTypes(): array
    {
        return [
            PhoneNumberType::FIXED_LINE_OR_MOBILE,
            PhoneNumberType::FIXED_LINE,
            PhoneNumberType::MOBILE,
            PhoneNumberType::SHARED_COST,
            PhoneNumberType::VOIP,
            PhoneNumberType::TOLL_FREE,
            PhoneNumberType::STANDARD_RATE,
            PhoneNumberType::PERSONAL_NUMBER,
            PhoneNumberType::PAGER,
        ];
    }

    public string $message = 'The value {{ value }} is not a valid phone number.';
    public string $notIntlFormatMessage = 'The value {{ value }} is not formatted as an international phone number.';
    public string $tooShortMessage = 'The phone number {{ value }} is too short.';
    public string $tooLongMessage = 'The phone number {{ value }} is too long.';
    public string $inexistantMessage = 'The phone number {{ value }} does not exist.';
    public string $wrongTypeMessage = 'The value {{ value }} is not an accepted phone number type.';

    /**
     * {@inheritDoc}
     */
    public function validatedBy()
    {
        return self::class . 'Validator';
    }
}
