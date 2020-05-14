<?php

declare(strict_types=1);

namespace Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchRna;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchRnaValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class FrenchRnaValidatorTest extends ConstraintValidatorTestCase
{

    public function getConstraint($options = []): Constraint
    {
        return new FrenchRna($options);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new FrenchRnaValidator();
    }

    public function testGetSupportedConstraint()
    {
        $this->assertSame(FrenchRna::class, $this->validator->getSupportedConstraint());
    }

    /**
     * @dataProvider validValueDataProvider
     */
    public function testValidateValue(string $value)
    {
        $this->validator->validate($value, $this->getConstraint());
        $this->assertNoViolation();
    }

    public function validValueDataProvider()
    {
        yield 'Old Valid RNA' => ['891P0891000843'];
        yield 'Old Valid DOM-TOM RNA' => ['9R4S9744000501'];
        yield 'Old Valid Corse 2B RNA' => ['2B2P02B1000013'];
        yield 'Old Valid Corse 2A RNA' => ['2A4S02A4099171'];
        yield 'Classic Valid DOM-TOM RNA' => ['W9J1003281'];
        yield 'Classic RNA' => ['W941009978'];
        yield 'Classic Corse 2B RNA' => ['W2B2000016'];
        yield 'Classic Corse 2A RNA' => ['W2A1000119'];
    }

    /**
     * @dataProvider invalidValueDataProvider
     */
    public function testInvalidValue(string $value)
    {
        $this->validator->validate($value, $this->getConstraint());

        $this->buildViolation('This value {{ value }} is not valid.')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(FrenchRna::INVALID_FORMAT_ERROR)
            ->assertRaised();
    }

    public function invalidValueDataProvider()
    {
        yield 'RNA with no W at the beginning' => ['A941009978'];
        yield 'Too short RNA' => ['W94100997'];
        yield 'Too long RNA (new format) or too short (old format)' => ['W9410055997'];
        yield 'Classic RNA with lowercase letter' => ['W9j1003281'];
        yield 'Old RNA with a lowercase letter' => ['9r4S9744000501'];
        yield 'Old Corse 2B RNA 2C instead of 2B' => ['2B2P02C1000013'];
        yield 'Old Corse 2A RNA 1A instead of 2A' => ['2A4S01A4099171'];
        yield 'Classic RNA with invalid letter' => ['W2Y2000016'];
    }
}
