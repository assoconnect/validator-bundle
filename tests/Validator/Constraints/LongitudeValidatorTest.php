<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\LongitudeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class LongitudeValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Longitude($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new LongitudeValidator();
    }

    public function testIsEmptyStringAccepted()
    {
        $this->assertFalse($this->validator->isEmptyStringAccepted());
    }

    public function testGetSupportedConstraint()
    {
        $this->assertSame(Longitude::class, $this->validator->getSupportedConstraint());
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
            $this->validator->getConstraints($value, $this->getConstraint())
        );
    }

    public function getConstraintsProvider(): array
    {
        return [
            [18.1, [new GreaterThanOrEqual(-90), new LessThanOrEqual(90)]],
            [18, [new GreaterThanOrEqual(-90), new LessThanOrEqual(90)]],
            ['18', [new Type('float')]]
        ];
    }
}
