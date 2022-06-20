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
            PhoneNumberType::UAN,
            PhoneNumberType::VOIP,
        ];
    }

    public string $wrongTypeMessage = 'The value {{ value }} is not a valid landline phone number.';
}
