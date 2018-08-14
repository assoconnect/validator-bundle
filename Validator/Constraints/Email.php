<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Email as _Email;

/**
 * {@inheritdoc}
 */
Class Email extends _Email
{
    const INVALID_TLD_ERROR = 'd125a480-3efd-45dd-9a59-6058fccc4fe4';

    public $TLDMessage = 'This email does not have a valid domain name.';

    public function __construct($options = null)
    {
        // Parent constructor
        parent::__construct($options);
        // Add constant in $errorName
        self::$errorNames += [
            self::INVALID_TLD_ERROR => 'INVALID_TLD_ERROR',
        ];
    }
}