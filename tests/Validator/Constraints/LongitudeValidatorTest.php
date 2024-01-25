<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Test\ConstraintValidatorTestCase;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use AssoConnect\ValidatorBundle\Validator\Constraints\LongitudeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class LongitudeValidatorTest extends ConstraintValidatorTestCase
{
    protected function getConstraint(): Constraint
    {
        return new Longitude();
    }

    public function createValidator(): ConstraintValidatorInterface
    {
        return new LongitudeValidator();
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
            [
                '{{ pattern }}' => LongitudeValidator::REGEX,
                '{{ value }}' => '"hello"',
            ],
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
            '-181',
            GreaterThanOrEqual::TOO_LOW_ERROR,
            'This value should be greater than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '"-181"',
                '{{ compared_value }}' => '-180',
                '{{ compared_value_type }}' => 'int',
            ],
        ];

        yield [
            '181',
            LessThanOrEqual::TOO_HIGH_ERROR,
            'This value should be less than or equal to {{ compared_value }}.',
            [
                '{{ value }}' => '"181"',
                '{{ compared_value }}' => '180',
                '{{ compared_value_type }}' => 'int',
            ],
        ];
    }
}
