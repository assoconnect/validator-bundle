<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class SpanishNif extends Constraint
{
    public const string WRONG_FORMAT_ERROR = 'a1b2c3d4-e5f6-7890-abcd-ef1234567890';
    public const string MESSAGE = 'The Spanish NIF {{ value }} is not valid.';
}
