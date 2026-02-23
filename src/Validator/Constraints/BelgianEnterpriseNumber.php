<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class BelgianEnterpriseNumber extends Constraint
{
    public const string WRONG_FORMAT_ERROR = '3f7d2e1c-a845-4b9f-c631-52d78e04fb6a';
    public const string MESSAGE = 'The Belgian Enterprise Number {{ value }} is not valid.';
}
