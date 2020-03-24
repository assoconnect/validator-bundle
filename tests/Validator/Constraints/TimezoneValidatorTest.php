<?php

namespace AssoConnect\ValidatorBundle\Validator\Tests\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Timezone;
use AssoConnect\ValidatorBundle\Validator\Constraints\TimezoneValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TimezoneValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Timezone($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new TimezoneValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Timezone());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Timezone());

        $this->assertNoViolation();
    }

    /**
     * @expectedException Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $this->validator->validate(new \stdClass(), new Timezone());
    }

    /**
     * @dataProvider getValidTimezones
     * @param $timezone
     */
    public function testValidTimezones($timezone)
    {
        $this->validator->validate($timezone, new Timezone());

        $this->assertNoViolation();
    }

    public function getValidTimezones()
    {
        return array(
            array('Europe/Paris'),
            array('Europe/Berlin'),
        );
    }

    /**
     * @dataProvider getInvalidTimezones
     * @param $timezone
     */
    public function testInvalidTimezones($timezone)
    {
        $this->validator->validate($timezone, $this->getConstraint(['message' => 'myMessage']));

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $timezone . '"')
            ->setCode(Timezone::NO_SUCH_TIMEZONE_ERROR)
            ->assertRaised();
    }

    public function getInvalidTimezones()
    {
        return [
            ['EN'],
            ['foobar'],
        ];
    }
}
