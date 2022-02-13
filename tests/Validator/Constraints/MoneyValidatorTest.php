<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;
use AssoConnect\ValidatorBundle\Validator\Constraints\MoneyValidator;
use Ramsey\Collection\Map\TypedMap;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class MoneyValidatorTest extends ConstraintValidatorTestCase
{
    public function getConstraint(): Constraint
    {
        return new Money(['min' => 0.0, 'max' => 90.0]);
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new MoneyValidator();
    }

    public function providerValidValues(): iterable
    {
        yield [18];
        yield [18.1];
    }

    public function providerInvalidValues(): iterable
    {
        yield 'wrong type' => [
            '18',
            Type::INVALID_TYPE_ERROR,
            'This value should be of type {{ type }}.',
            [
                '{{ type }}' => 'float',
                '{{ value }}' => '"18"'
            ]
        ];

        yield [
            '',
            NotBlank::IS_BLANK_ERROR,
            'This value should not be blank.'
        ];

        yield [
            -10,
            GreaterThanOrEqual::TOO_LOW_ERROR,
            'This value should be greater than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '-10',
                '{{ compared_value_type }}' => 'float',
                '{{ compared_value }}' => 0,
            ]
        ];

        yield [
            100,
            LessThan::TOO_HIGH_ERROR,
            'This value should be less than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '100',
                '{{ compared_value_type }}' => 'float',
                '{{ compared_value }}' => '90',
            ]
        ];
    }
}
