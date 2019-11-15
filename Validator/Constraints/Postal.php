<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 */
class Postal extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'ea4eb1eb-3984-4455-9b00-dcbf5221f933';

    public const MISSING_ERROR = '799ee5b5-5cc6-4d85-abd3-8efdfd536f29';

    public const NOT_REQUIRED_ERROR = '4c07fa49-108a-49af-9983-f547ee477351';

    protected static $errorNames = array(
        self::INVALID_FORMAT_ERROR => 'The postal {{ value }} does not have a valid format',
        self::MISSING_ERROR => 'The country requires a postal',
        self::NOT_REQUIRED_ERROR => 'The country does not require a postal',
    );

    public $countryPropertyPath;
}
