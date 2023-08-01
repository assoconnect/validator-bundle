<?php

declare(strict_types=1);

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

    public string $message = 'The value {{ value }} is not a valid timezone.';
}
