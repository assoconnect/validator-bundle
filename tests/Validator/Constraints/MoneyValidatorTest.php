<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use AssoConnect\ValidatorBundle\Validator\Constraints\MoneyValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class MoneyValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Money($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new MoneyValidator();
    }

    public function testIsEmptyStringAccepted()
    {
        $this->assertFalse($this->validator->isEmptyStringAccepted());
    }

    public function testGetSupportedConstraint()
    {
        $this->assertSame(Money::class, $this->validator->getSupportedConstraint());
    }

    /**
     * @dataProvider getConstraintsProvider
     * @param $value
     * @param $constraints
     */
    public function testGetConstraints($value, $constraints)
    {
        $this->assertArrayContainsSameObjects(
            $constraints,
            $this->validator->getConstraints($value, $this->getConstraint(['min' => 0, 'max' => 90]))
        );
    }

    public function getConstraintsProvider(): array
    {
        return [
            [18.1, [new GreaterThanOrEqual(0), new LessThan(90)]],
            [18, [new GreaterThanOrEqual(0), new LessThan(90)]],
            ['18', [new Type('float')]]
        ];
    }
}
