<?php

declare(strict_types=1);

namespace Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSirenValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class FrenchSirenValidatorTest extends ConstraintValidatorTestCase
{

    public function getConstraint($options = []): Constraint
    {
        return new FrenchSiren($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new FrenchSirenValidator();
    }

    public function testGetSupportedConstraint()
    {
        $this->assertSame(FrenchSiren::class, $this->validator->getSupportedConstraint());
    }

    public function testValidateValue()
    {
        $this->validator->validate('732829320', $this->getConstraint());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider invalidValueDataProvider
     */
    public function testInvalidValue(string $value, string $error)
    {
        $this->validator->validate($value, $this->getConstraint());

        $this->buildViolation('This value {{ value }} is not valid.')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode($error)
            ->assertRaised();
    }

    public function invalidValueDataProvider()
    {
        yield 'SIREN with alphabetic character' => ['123456A789', FrenchSiren::INVALID_FORMAT_ERROR];
        yield 'Empty SIREN' => ['', FrenchSiren::INVALID_FORMAT_ERROR];
        yield 'Too short SIREN' => ['73282320', FrenchSiren::INVALID_FORMAT_ERROR];
        yield 'Wrong SIREN' => ['732829321', FrenchSiren::CHECKSUM_FAILED_ERROR];
    }

}
