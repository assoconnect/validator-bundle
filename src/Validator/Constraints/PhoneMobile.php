<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use libphonenumber\PhoneNumberType;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class PhoneMobile extends Phone
{
    public $validTypes = [
        PhoneNumberType::FIXED_LINE_OR_MOBILE,
        PhoneNumberType::MOBILE,
    ];

    public $invalidTypes = [
        PhoneNumberType::FIXED_LINE,
        PhoneNumberType::SHARED_COST,
        PhoneNumberType::VOIP,
    ];

    public $messageInvalidType = 'This value is not a valid mobile phone number.';
}
