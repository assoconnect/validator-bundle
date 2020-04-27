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
    public const INVALID_TYPE_ERROR = 'e4b85033-1205-44e2-b6b4-e6a8310a22df';
    public const LENGTH_MAX_ERROR = '4e5a9535-133a-4ee5-a0ae-22e519bd3f94';
    public const LENGTH_MIN_ERROR = 'd8a6f526-e6e0-4ac3-9608-0f9936a3773c';
    public const PHONE_NUMBER_NOT_EXIST = 'f32ef12d-cefa-42d9-97f0-520d90276bf0';

    protected static $errorNames = array(
        self::INVALID_FORMAT_ERROR => 'STRICT_CHECK_FAILED_ERROR',
        self::INVALID_TYPE_ERROR => 'INVALID_TYPE_ERROR',
        self::LENGTH_MAX_ERROR => 'LENGTH_MAX_ERROR',
        self::LENGTH_MIN_ERROR => 'LENGTH_MIN_ERROR',
        self::PHONE_NUMBER_NOT_EXIST => 'PHONE_NUMBER_NOT_EXIST',
    );

    public $validTypes = [
        PhoneNumberType::FIXED_LINE_OR_MOBILE,
        PhoneNumberType::FIXED_LINE,
        PhoneNumberType::MOBILE,
        PhoneNumberType::SHARED_COST,
        PhoneNumberType::VOIP,
    ];

    public $invalidTypes = [];

    public $message = 'This value is not a valid phone number.';
    public $tooShortMessage = 'This phone number is too short.';
    public $tooLongMessage = 'This phone number is too long.';
    public $inexistantMessage = 'This phone number does not exist.';
    public $invalidTypeMessage = 'This type of phone number is not accepted.';

    /**
     * {@inheritDoc}
     */
    public function validatedBy()
    {
        return self::class . 'Validator';
    }
}
