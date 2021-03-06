<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Phone($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new PhoneValidator();
    }

    public function testValidateUnknownConstraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('phone', new NotNull());
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Phone());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Phone());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider providerInvalidValues
     * @param $constraint
     * @param $value
     * @param $messageField
     * @param $code
     */
    public function testInvalidValues($constraint, $value, $messageField, $code)
    {
        $this->validator->validate(
            $value,
            new $constraint([
                $messageField => 'myMessage',
            ])
        );
        $this
            ->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode($code)
            ->assertRaised();
    }
    public function providerInvalidValues()
    {
        return [
            // Not a number
            [Phone::class, 'ABC', 'message', Phone::INVALID_FORMAT_ERROR],
            // Does not exist
            [Phone::class, '+3310000000', 'inexistantMessage', Phone::PHONE_NUMBER_NOT_EXIST],
            // Invalid type
            [PhoneLandline::class, '+33623456789', 'invalidTypeMessage', Phone::INVALID_TYPE_ERROR],
            [PhoneMobile::class, '+33123456789', 'invalidTypeMessage', Phone::INVALID_TYPE_ERROR],
        ];
    }

    /**
     * @dataProvider providerValidValues
     * @param $constraint
     * @param $value
     */
    public function testValidValues($constraint, $value)
    {
        $this->validator->validate($value, new $constraint());
        $this->assertNoViolation();
    }
    public function providerValidValues()
    {
        return [
            [Phone::class, '+33123456789'],
            [Phone::class, '+33623456789'],
            [PhoneLandline::class, '+33123456789'],
            [PhoneMobile::class, '+33623456789'],
        ];
    }
}
