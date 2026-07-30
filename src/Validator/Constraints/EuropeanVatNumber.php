<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class EuropeanVatNumber extends Constraint
{
    public const string INVALID_FORMAT_ERROR = '94d6569c-5f24-413f-be89-bf72c0116ce5';
    public const string MESSAGE = 'The intra-EU VAT number {{ value }} is not valid.';
}
