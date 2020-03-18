<?php

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates whether a value is a valid locale code.
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class TimezoneValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Timezone) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Timezone');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!in_array($value, \DateTimeZone::listIdentifiers())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Timezone::NO_SUCH_TIMEZONE_ERROR)
                ->addViolation();
        }
    }
}
