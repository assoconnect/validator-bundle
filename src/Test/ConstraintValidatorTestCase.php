<?php

namespace AssoConnect\ValidatorBundle\Test;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase as SymfonyConstraintValidatorTestCase;

abstract class ConstraintValidatorTestCase extends SymfonyConstraintValidatorTestCase
{
    abstract public function getConstraint($options = []): Constraint;

    abstract public function createValidator(): ConstraintValidatorInterface;

    protected function assertArrayContainsSameObjects(array $array1, array $array2, $message = '')
    {
        self::assertThat($array1, new ArrayContainSameObjectsConstraint($array2), $message);
    }

}
