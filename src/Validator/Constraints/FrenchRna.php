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
class FrenchRna extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'd125c480-3efd-42dd-9a59-6058fddd4fe4';

    public $message = 'This value {{ value }} is not valid.';
}
