<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use libphonenumber\PhoneNumberType;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class PhoneLandline extends Phone
{
    public function getValidTypes(): array
    {
        return [
            PhoneNumberType::FIXED_LINE_OR_MOBILE,
            PhoneNumberType::FIXED_LINE,
            PhoneNumberType::PERSONAL_NUMBER,
            PhoneNumberType::PREMIUM_RATE,
            PhoneNumberType::SHARED_COST,
            PhoneNumberType::STANDARD_RATE,
            PhoneNumberType::TOLL_FREE,
            PhoneNumberType::UAN,
            PhoneNumberType::VOIP,
        ];
    }

    public string $wrongTypeMessage = 'The value {{ value }} is not a valid landline phone number.';
}
