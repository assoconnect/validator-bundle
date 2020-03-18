<?php

namespace AssoConnect\ValidatorBundle\Validator\Tests\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Timezone;
use AssoConnect\ValidatorBundle\Validator\Constraints\TimezoneValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TimezoneValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
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
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $this->validator->validate(new \stdClass(), new Timezone());
    }

    /**
     * @dataProvider getValidTimezones
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
     */
    public function testInvalidTimezones($timezone)
    {
        $constraint = new Timezone(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate($timezone, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $timezone . '"')
            ->setCode(Timezone::NO_SUCH_TIMEZONE_ERROR)
            ->assertRaised();
    }

    public function getInvalidTimezones()
    {
        return array(
            array('EN'),
            array('foobar'),
        );
    }
}
