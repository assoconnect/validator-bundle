<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Postal;
use AssoConnect\ValidatorBundle\Validator\Constraints\PostalValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PostalValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new Postal([
            'countryPropertyPath' => 'country',
        ]);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new PostalValidator();
    }

    public function testMissingPropertyPath(): void
    {
        $this->expectException(MissingOptionsException::class);
        new Postal();
    }

    public function testMissingObject(): void
    {
        $this->setObject(null);

        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate(null, $this->getConstraint());
    }

    public function testInvalidPropertyPath(): void
    {
        $object = new \stdClass();
        $object->country2 = 'KK';

        $this->setObject($object);

        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate(null, $this->getConstraint());
    }

    public function testUnexpectedCountry(): void
    {
        $object = new \stdClass();
        $object->country = 'KK';

        $this->setObject($object);

        $this->validator->validate(null, $this->getConstraint());

        $this
            ->buildViolation('The country {{ country }} is unknown.')
            ->setParameter('{{ country }}', '"KK"')
            ->setCode(Postal::UNKNOWN_COUNTRY_ERROR)
            ->assertRaised()
        ;
    }

    public function testMissingPostalError(): void
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate(null, $this->getConstraint());

        $this
            ->buildViolation('The postal code must be provided.')
            ->setCode(Postal::MISSING_ERROR)
            ->assertRaised()
        ;
    }

    public function testNoPostalCodeSystem(): void
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $this->validator->validate('postal', $this->getConstraint());

        $this
            ->buildViolation('There is no postal code system in the country {{ country }}.')
            ->setCode(Postal::NO_POSTAL_CODE_SYSTEM)
            ->setParameter('{{ country }}', '"AE"')
            ->assertRaised()
        ;
    }

    public function testInvalidPostalFormatError(): void
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate('foo', $this->getConstraint());

        $this
            ->buildViolation('The value {{ value }} is not a valid postal code.')
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Postal::INVALID_FORMAT_ERROR)
            ->assertRaised()
        ;
    }

    public function testPostalRequiredNoViolation(): void
    {
        $object = new \stdClass();
        $object->country = 'FR';

        $this->setObject($object);

        $this->validator->validate('75002', $this->getConstraint());

        self::assertNoViolation();
    }

    public function testPostalNotRequiredNoViolation(): void
    {
        $object = new \stdClass();
        $object->country = 'AE';

        $this->setObject($object);

        $this->validator->validate('', $this->getConstraint());

        self::assertNoViolation();
    }

    public function testCountryNullWithPostalNull(): void
    {
        $object = new \stdClass();
        $object->country = null;

        $this->setObject($object);

        $this->validator->validate(null, $this->getConstraint());

        self::assertNoViolation();
    }
}
