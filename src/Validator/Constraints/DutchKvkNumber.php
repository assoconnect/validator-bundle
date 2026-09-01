<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class DutchKvkNumber extends Constraint
{
    public const string WRONG_FORMAT_ERROR = '16a7f6c0-e16e-4db3-8ac0-4072bd00ed25';
    public const string MESSAGE = 'The Dutch KVK Number {{ value }} is not valid.';
}
