<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class Timezone extends Constraint
{
    public const NO_SUCH_TIMEZONE_ERROR = 'a0af4293-1f1a-4a1c-a328-979dbf6182a2';

    protected static $errorNames = array(
        self::NO_SUCH_TIMEZONE_ERROR => 'NO_SUCH_TIMEZONE_ERROR',
    );

    public $message = 'This value is not a valid timezone.';
}
