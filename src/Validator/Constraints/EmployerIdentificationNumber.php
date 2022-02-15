<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmployerIdentificationNumber extends Constraint
{
    public const WRONG_FORMAT_ERROR = '890163a8-f289-4c6b-aa2b-73890f93ca5c';
    public string $message = 'The value {{ value }} is not a valid employer identification number.';
}
