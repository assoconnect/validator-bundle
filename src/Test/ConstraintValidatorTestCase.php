<?php

namespace AssoConnect\ValidatorBundle\Test;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase as SymfonyConstraintValidatorTestCase;

abstract class ConstraintValidatorTestCase extends SymfonyConstraintValidatorTestCase
{
    abstract public function getConstraint($options = []): Constraint;

    protected function assertArrayContainsSameObjects(array $array1, array $array2, $message = '')
    {
        $this->assertSameSize($array1, $array2);

        $classes1 = array_map('get_class', $array1);
        sort($classes1);

        $classes2 = array_map('get_class', $array2);
        sort($classes2);

        $this->assertSame($classes1, $classes2);
    }
}
