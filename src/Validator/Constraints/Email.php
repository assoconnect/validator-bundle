<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Email as _Email;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class Email extends _Email
{
    public const INVALID_TLD_ERROR = 'd125a480-3efd-45dd-9a59-6058fccc4fe4';
    public const INVALID_DNS_ERROR = '5fca88ed-2534-4f78-b02d-397c111274ed';

    public $tldMessage = '{{ domain }} is not a valid domain name.';
    public $dnsMessage = '{{ domain }} is not setup to received emails.';

    public $mode = 'strict';

    public $checkDNS = false;

    public function __construct($options = null)
    {
        // Parent constructor
        parent::__construct($options);
        // Add constant in $errorName
        self::$errorNames += [
            self::INVALID_TLD_ERROR => 'INVALID_TLD_ERROR',
            self::INVALID_DNS_ERROR => 'INVALID_DNS_ERROR',
        ];
    }
}
