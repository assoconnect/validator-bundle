<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use AssoConnect\ValidatorBundle\Validator\Constraints\PostalValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PostalValidatorTest extends ConstraintValidatorTestCase
{
    public function createValidator()
    {
        return new PostalValidator();
    }

    public function testInvalidConstraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(null, new Email());
    }

    public function testMissingObject()
    {
        $this->setObject(null);

        $this->expectException(ConstraintDefinitionException::class);

        $this->validator->validate(null, new Postal('propertyPath'));
    }

    public function testInvalidPropertyPath()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $this->expectException(ConstraintDefinitionException::class);

        $this->validator->validate(null, new Postal('invalid'));
    }

    public function testUnexpectedCountry()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $this->validator->validate(null, new Postal('country'));

        $this
            ->buildViolation(Postal::getErrorName(Postal::UNEXPECTED_COUNTRY_ERROR))
            ->setParameter('{{ value }}', '"KK"')
            ->setCode(Postal::UNEXPECTED_COUNTRY_ERROR)
            ->assertRaised()
        ;
    }

    public function testMissingPostalError()
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate(null, new Postal('country'));

        $this
            ->buildViolation(Postal::getErrorName(Postal::MISSING_ERROR))
            ->setCode(Postal::MISSING_ERROR)
            ->assertRaised()
        ;
    }

    public function testPostalNotRequiredError()
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $this->validator->validate('postal',  new Postal('country'));

        $this
            ->buildViolation(Postal::getErrorName(Postal::NOT_REQUIRED_ERROR))
            ->setCode(Postal::NOT_REQUIRED_ERROR)
            ->assertRaised()
        ;
    }

    public function testInvalidPostalFormatError()
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate('foo', new Postal('country'));

        $this
            ->buildViolation(Postal::getErrorName(Postal::INVALID_FORMAT_ERROR))
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Postal::INVALID_FORMAT_ERROR)
            ->assertRaised()
        ;
    }

    public function testPostalRequiredNoViolation()
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate('75002', new Postal('country'));

        $this->assertNoViolation();
    }

    public function testPostalNotRequiredNoViolation()
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $this->validator->validate('', new Postal('country'));

        $this->assertNoViolation();
    }

    public function testCountryNullWithPostalNull()
    {
        $object = new \StdClass();
        $object->country = null;

        $this->setObject($object);

        $this->validator->validate(null, new Postal('country'));

        $this->assertNoViolation();
    }
}
