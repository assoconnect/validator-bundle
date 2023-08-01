<?php

declare(strict_types=1);

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
    public function getValidTypes(): array
    {
        return [
            PhoneNumberType::FIXED_LINE_OR_MOBILE,
            PhoneNumberType::MOBILE,
            PhoneNumberType::PAGER,
            PhoneNumberType::PERSONAL_NUMBER,
        ];
    }

    public string $wrongTypeMessage = 'The value {{ value }} is not a valid mobile phone number.';
}
