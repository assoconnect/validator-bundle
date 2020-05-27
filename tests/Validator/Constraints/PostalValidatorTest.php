<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use AssoConnect\ValidatorBundle\Validator\Constraints\PostalValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

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

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testIncorrectConstraint()
    {
        $this->validator->validate(null, new Email());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testMissingObject()
    {
        $this->setObject(null);

        $this->validator->validate(null, $this->getConstraint('propertyPath'));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidPropertyPath()
    {
        $this->setObject($this->createTestObject('KK'));

        $this->validator->validate(null, $this->getConstraint('invalid'));
    }

    public function testUnexpectedCountry()
    {
        $this->setObject($this->createTestObject('KK'));

        $this->validator->validate(null, $this->getConstraint(['countryPropertyPath' => 'country']));

        $this
            ->buildViolation(Postal::getErrorName(Postal::UNEXPECTED_COUNTRY_ERROR))
            ->setParameter('{{ value }}', '"KK"')
            ->setCode(Postal::UNEXPECTED_COUNTRY_ERROR)
            ->assertRaised()
        ;
    }

    public function testMissingPostalError()
    {
        $this->setObject($this->createTestObject('FR'));

        $this->validator->validate(null, $this->getConstraint(['countryPropertyPath' => 'country']));

        $this
            ->buildViolation(Postal::getErrorName(Postal::MISSING_ERROR))
            ->setCode(Postal::MISSING_ERROR)
            ->assertRaised()
        ;
    }

    public function testPostalNotRequiredError()
    {
        $this->setObject($this->createTestObject('AE'));

        $this->validator->validate('postal', $this->getConstraint(['countryPropertyPath' => 'country']));

        $this
            ->buildViolation(Postal::getErrorName(Postal::NOT_REQUIRED_ERROR))
            ->setCode(Postal::NOT_REQUIRED_ERROR)
            ->assertRaised()
        ;
    }

    public function testInvalidPostalFormatError()
    {
        $this->setObject($this->createTestObject('FR'));

        $this->validator->validate('foo', $this->getConstraint(['countryPropertyPath' => 'country']));

        $this
            ->buildViolation(Postal::getErrorName(Postal::INVALID_FORMAT_ERROR))
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Postal::INVALID_FORMAT_ERROR)
            ->assertRaised()
        ;
    }

    public function testPostalRequiredNoViolation()
    {
        $this->setObject($this->createTestObject('FR'));

        $this->validator->validate('75002', $this->getConstraint(['countryPropertyPath' => 'country']));

        $this->assertNoViolation();
    }

    public function testPostalNotRequiredNoViolation()
    {
        $this->setObject($this->createTestObject('AE'));

        $this->validator->validate('', $this->getConstraint(['countryPropertyPath' => 'country']));

        $this->assertNoViolation();
    }

    public function testCountryNullWithPostalNull()
    {
        $this->setObject($this->createTestObject());

        $this->validator->validate(null, $this->getConstraint(['countryPropertyPath' => 'country']));

        $this->assertNoViolation();
    }

    private function createTestObject($country = null): \StdClass
    {
        $object = new \StdClass();
        $object->country = $country;

        return $object;
    }
}
