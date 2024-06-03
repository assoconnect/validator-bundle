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
class UsSocialSecurityNumber extends Constraint
{
    public const INVALID_FORMAT_ERROR = '10a8c9a0-8e5c-11ec-90d6-0242ac120003';

    public string $message = 'The value {{ value }} is not a valid US Social Security Number.';
}
