<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class FloatScale extends Constraint
{
    public const TOO_PRECISE_ERROR = '1cb54d2e-1258-490d-b2cc-91a0a21d9556';

    public $message = 'The float precision is limited to {{ scale }} numbers.';

    public $scale;

    public function getDefaultOption()
    {
        return 'scale';
    }

    public function getRequiredOptions()
    {
        return ['scale'];
    }
}
