<?php

namespace AssoConnect\ValidatorBundle\Dto;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidatorAndConstraint
{
    private ConstraintValidator $validator;
    private Constraint $constraint;

    public function __construct(ConstraintValidator $validator, Constraint $constraint)
    {
        $this->validator = $validator;
        $this->constraint = $constraint;
    }

    public function getValidator(): ConstraintValidator
    {
        return $this->validator;
    }

    public function getConstraint(): Constraint
    {
        return $this->constraint;
    }
}
