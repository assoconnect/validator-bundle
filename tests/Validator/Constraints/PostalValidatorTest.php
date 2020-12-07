<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use AssoConnect\ValidatorBundle\Validator\Constraints\PostalValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PostalValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint($options = []): Constraint
    {
        return new Postal($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new PostalValidator();
    }

    public function testIncorrectConstraint()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(null, new Email());
    }

    public function testMissingPropertyPath()
    {
        $this->expectException(MissingOptionsException::class);
        $this->getConstraint(null);
    }

    public function testMissingObject()
    {
        $this->setObject(null);

        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate(null, $this->getConstraint('propertyPath'));
    }

    public function testInvalidPropertyPath()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate(null, $this->getConstraint('invalid'));
    }

    public function testUnexpectedCountry()
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $this->validator->validate(null, $this->getConstraint('country'));

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

        $this->validator->validate(null, $this->getConstraint('country'));

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

        $this->validator->validate('postal', $this->getConstraint('country'));

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

        $this->validator->validate('foo', $this->getConstraint('country'));

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

        $this->validator->validate('75002', $this->getConstraint('country'));

        $this->assertNoViolation();
    }

    public function testPostalNotRequiredNoViolation()
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $this->validator->validate('', $this->getConstraint('country'));

        $this->assertNoViolation();
    }

    public function testCountryNullWithPostalNull()
    {
        $object = new \StdClass();
        $object->country = null;

        $this->setObject($object);

        $this->validator->validate(null, $this->getConstraint('country'));

        $this->assertNoViolation();
    }
}
