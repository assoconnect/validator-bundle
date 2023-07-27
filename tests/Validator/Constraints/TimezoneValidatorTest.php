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
    protected function getConstraint(): Constraint
    {
        return new Timezone();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new TimezoneValidator();
    }

    public function testExpectsStringCompatibleType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new Timezone());
    }

    public function providerValidValues(): iterable
    {
        yield [null];
        yield [''];
        yield ['Europe/Paris'];
        yield ['Europe/Berlin'];
        yield [new \DateTimeZone('Europe/Paris')];
    }

    public function providerInvalidValues(): iterable
    {
        yield [
            'EN',
            Timezone::NO_SUCH_TIMEZONE_ERROR,
            'The value {{ value }} is not a valid timezone.'
        ];
    }
}
