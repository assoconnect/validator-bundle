<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Percent;
use AssoConnect\ValidatorBundle\Validator\Constraints\PercentValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class PercentValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Percent($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new PercentValidator();
    }

    public function testIsEmptyStringAccepted()
    {
        $this->assertFalse($this->validator->isEmptyStringAccepted());
    }

    public function testGetSupportedConstraint()
    {
        $this->assertSame(Percent::class, $this->validator->getSupportedConstraint());
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
            [18.1, [new GreaterThanOrEqual(0), new LessThanOrEqual(90)]],
            [18, [new GreaterThanOrEqual(0), new LessThanOrEqual(90)]],
            ['18', [new Type('float')]]
        ];
    }

    public function providerValidValue(): array
    {
        return [
            [null],
            [0.0],
            [0],
            [25.0],
            [100.0],
            [100],
        ];
    }

    public function providerInvalidValue(): array
    {
        return [
            // Value type
            ['', array(), [Type::INVALID_TYPE_ERROR]],
            // Default range
            [-1.0, array(), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [101.0, array(), [LessThanOrEqual::TOO_HIGH_ERROR]],
            // Custom range
            [0.0, array('min' => 10), [GreaterThanOrEqual::TOO_LOW_ERROR]],
            [11.0, array('max' => 10), [LessThanOrEqual::TOO_HIGH_ERROR]],
        ];
    }
}
