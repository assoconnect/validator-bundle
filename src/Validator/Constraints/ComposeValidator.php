<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class ComposeValidator extends ConstraintValidator
{
    abstract protected function getSupportedConstraint(): string;

    abstract protected function getConstraints($value, Constraint $constraint): array;

    abstract protected function isEmptyStringAccepted(): bool;

    public function validate($value, Constraint $constraint)
    {
        $supportedConstraint = $this->getSupportedConstraint();
        if (get_class($constraint) !== $supportedConstraint) {
            throw new UnexpectedTypeException($constraint, $supportedConstraint);
        }

        $constraints = $this->getConstraints($value, $constraint);

        if ($value === null) {
            return;
        }

        if ($value === '') {
            if ($this->isEmptyStringAccepted()) {
                return;
            }
        }

        $validator = $this->context->getValidator()->inContext($this->context);
        $validator->validate($value, $constraints);
    }
}
