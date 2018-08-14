<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

Abstract class ComposeValidator extends ConstraintValidator
{

    protected abstract function getSupportedConstraint() :string;

    protected abstract function getConstraints($value, Constraint $constraint) :array;

    protected abstract function isEmptyStringAccepted() :bool;

    public function validate($value, Constraint $constraint)
    {
        $supportedConstraint = $this->getSupportedConstraint();
        if(get_class($constraint) !== $supportedConstraint) {
            throw new UnexpectedTypeException($constraint, $supportedConstraint);
        }

        $constraints = $this->getConstraints($value, $constraint);

        if($value === null){
            return;
        }

        if($value === ''){
            if($this->isEmptyStringAccepted()){
                return;
            }
        }

        $validator = $this->context->getValidator()->inContext($this->context);
        $validator->validate($value, $constraints);

    }

}