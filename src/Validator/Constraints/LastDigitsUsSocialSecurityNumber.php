<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Florian Guimier <florian.guimier@assoconnect.com>
 */
class LastDigitsUsSocialSecurityNumber extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'a6e7e8b3-2e2c-4f7c-b321-0b3b8e3c87f1';

    public string $message = 'The value {{ value }} is not a valid set of ' .
    'last four digits of a US Social Security Number.';
}
