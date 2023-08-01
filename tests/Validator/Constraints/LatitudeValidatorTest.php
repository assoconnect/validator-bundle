<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\LatitudeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class LatitudeValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new Latitude();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new LatitudeValidator();
    }

    public function providerValidValues(): iterable
    {
        yield ['18'];
    }

    public function providerInvalidValues(): iterable
    {
        yield [
            'hello',
            Regex::REGEX_FAILED_ERROR,
            'This value is not valid.',
        ];

        yield [
            '',
            NotBlank::IS_BLANK_ERROR,
            'This value should not be blank.',
        ];

        yield [
            42,
            Type::INVALID_TYPE_ERROR,
            'This value should be of type {{ type }}.',
            [
                '{{ value }}' => '42',
                '{{ type }}' => 'string',
            ],
        ];

        yield [
            '-91',
            GreaterThanOrEqual::TOO_LOW_ERROR,
            'This value should be greater than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '"-91"',
                '{{ compared_value }}' => '-90',
                '{{ compared_value_type }}' => 'int',
            ],
        ];

        yield [
            '91',
            LessThanOrEqual::TOO_HIGH_ERROR,
            'This value should be less than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '"91"',
                '{{ compared_value }}' => '90',
                '{{ compared_value_type }}' => 'int',
            ],
        ];
    }
}
