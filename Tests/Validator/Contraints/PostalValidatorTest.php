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

    public function testMissingPropertyPath()
    {
        $constraint = new Postal();
        $constraint->countryPropertyPath = null;

        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate(null, $constraint);
    }

    public function testMissingObject()
    {
        $this->setObject(null);

        $constraint = new Postal();
        $constraint->countryPropertyPath = 'propertyPath';

        $this->expectException(ConstraintDefinitionException::class);

        $this->validator->validate(null, $constraint);
    }

    public function testInvalidPropertyPath()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $constraint = new Postal();
        $constraint->countryPropertyPath = 'invalid';

        $this->expectException(ConstraintDefinitionException::class);

        $this->validator->validate(null, $constraint);
    }

    public function testUnexpectedCountry()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $constraint = new Postal();
        $constraint->countryPropertyPath = 'country';

        $this->validator->validate(null, $constraint);

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

        $path = 'country';
        $constraint = new Postal();
        $constraint->countryPropertyPath = $path;

        $this->validator->validate(null, $constraint);

        $this
            ->buildViolation(Postal::getErrorName(Postal::MISSING_ERROR))
            ->setCode(Postal::MISSING_ERROR)
            ->assertRaised();
    }

    public function testPostalNotRequiredError()
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $path = 'country';
        $constraint = new Postal();
        $constraint->countryPropertyPath = $path;

        $this->validator->validate('postal', $constraint);

        $this
            ->buildViolation(Postal::getErrorName(Postal::NOT_REQUIRED_ERROR))
            ->setCode(Postal::NOT_REQUIRED_ERROR)
            ->assertRaised();
    }

    public function testInvalidPostalFormatError()
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $path = 'country';
        $constraint = new Postal();
        $constraint->countryPropertyPath = $path;

        $this->validator->validate('foo', $constraint);

        $this
            ->buildViolation(Postal::getErrorName(Postal::INVALID_FORMAT_ERROR))
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Postal::INVALID_FORMAT_ERROR)
            ->assertRaised();
    }

    public function testPostalRequiredNoViolation()
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $path = 'country';
        $constraint = new Postal();
        $constraint->countryPropertyPath = $path;

        $this->validator->validate('75002', $constraint);

        $this->assertNoViolation();
    }

    public function testPostalNotRequiredNoViolation()
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $path = 'country';
        $constraint = new Postal();
        $constraint->countryPropertyPath = $path;

        $this->validator->validate('', $constraint);

        $this->assertNoViolation();
    }
}
