<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
Class Money extends Constraint
{

    const MAX = 100000000.0;

    public $min = 0.0;
    public $max = self::MAX;

}