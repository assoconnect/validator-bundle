<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use AssoConnect\ValidatorBundle\Dto\ValidatorAndConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class ComposeValidator extends ConstraintValidator
{
    /**
     * @return class-string<Constraint>
     */
    abstract protected function getSupportedConstraint(): string;

    /**
     * @return array<ValidatorAndConstraint>
     */
    abstract protected function getValidatorsAndConstraints(mixed $value, Constraint $constraint): array;

    abstract protected function isEmptyStringAccepted(): bool;

    /**
     * Used when value type is not accepted by the recursive constraints used in validate() method
     */
    protected function sanitizeValue(mixed $value): mixed
    {
        return $value;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        $supportedConstraint = $this->getSupportedConstraint();
        if (get_class($constraint) !== $supportedConstraint) {
            throw new UnexpectedTypeException($constraint, $supportedConstraint);
        }

        if ($value === null) {
            return;
        }

        if ($value === '') {
            if (!$this->isEmptyStringAccepted()) {
                $validator = new NotBlankValidator();
                $validator->initialize($this->context);
                $validator->validate($value, new NotBlank());
            }
            return;
        }

        $value = $this->sanitizeValue($value);
        $validatorAndConstraints = $this->getValidatorsAndConstraints($value, $constraint);

        foreach ($validatorAndConstraints as $validatorAndConstraint) {
            $validator = $validatorAndConstraint->getValidator();
            $validator->initialize($this->context);
            $validator->validate($value, $validatorAndConstraint->getConstraint());
        }
    }
}
