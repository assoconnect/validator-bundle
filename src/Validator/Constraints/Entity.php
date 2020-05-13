<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Entity extends Constraint
{
    public $postalCountryPropertyPath = null;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
