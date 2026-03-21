<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class BelgianEnterpriseNumber extends Constraint
{
    // phpcs:ignore Generic.NamingConventions.UpperCaseConstantName -- PHP 8.3 typed constants not yet supported by sniff
    public const string WRONG_FORMAT_ERROR = '3f7d2e1c-a845-4b9f-c631-52d78e04fb6a';
    // phpcs:ignore Generic.NamingConventions.UpperCaseConstantName -- PHP 8.3 typed constants not yet supported by sniff
    public const string MESSAGE = 'The Belgian Enterprise Number {{ value }} is not valid.';
}
