<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Luhn;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Guillaume Wambre <guillaume.wambre@assoconnect.com>
 */
#[\Attribute]
class FrenchSiret extends Luhn
{
    public const INVALID_FORMAT_ERROR = 'cbe06561-776e-45c2-b33c-a73141746d43';

    /** @var mixed */
    public $message = 'The value {{ value }} is not a valid SIRET number.';
}
