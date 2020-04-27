<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScaleValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class FloatScaleValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new FloatScale($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new FloatScaleValidator();
    }

    public function testValidateValue()
    {
        $this->validator->validate(2.1, $this->getConstraint(['scale' => 2]));
        $this->assertNoViolation();
    }

    public function testValidateTooPrecise()
    {
        $this->validator->validate(0.0001, $this->getConstraint(['scale' => 2]));

        $this->buildViolation('The float precision is limited to {{ scale }} numbers.')
            ->setParameter('{{ scale }}', '2')
            ->setParameter('{{ value }}', '0.0001')
            ->setCode(FloatScale::TOO_PRECISE_ERROR)
            ->assertRaised();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateUnknownConstraint()
    {
        $this->validator->validate(0, new Email());
    }

    public function testValidateNotFloatValue()
    {
        $this->validator->validate('2.01', $this->getConstraint(['scale' => 2]));

        $this->assertNoViolation();
    }
}
