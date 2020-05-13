<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Luhn;

class FrenchSiren extends Luhn
{
    public const INVALID_FORMAT_ERROR = '4d762774-3g50-4bd5-a6d5-b10a3299d8d3';

    public $message = 'This value {{ value }} is not valid.';
}
