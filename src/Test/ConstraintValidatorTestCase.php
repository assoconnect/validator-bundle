<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ExpressionLanguageSyntax;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase as SymfonyConstraintValidatorTestCase;

/**
 * @template T of ConstraintValidator
 * @template-extends SymfonyConstraintValidatorTestCase<T>
 */
abstract class ConstraintValidatorTestCase extends SymfonyConstraintValidatorTestCase
{
    abstract protected function getConstraint(): Constraint;

    abstract public function createValidator(): ConstraintValidator;

    /**
     * @param mixed[] $array1
     * @param mixed[] $array2
     */
    protected static function assertArrayContainsSameObjects(array $array1, array $array2, string $message = ''): void
    {
        self::assertThat($array1, new ArrayContainSameObjectsConstraint($array2), $message);
    }

    public function testUnknownConstraintThrowsAnException(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(0, new ExpressionLanguageSyntax());
    }

    /**
     * @dataProvider providerValidValues
     */
    public function testValidValues(mixed $value): void
    {
        $this->validator->validate($value, $this->getConstraint());

        self::assertNoViolation();
    }

    /**
     * @return iterable<mixed>
     */
    abstract public function providerValidValues(): iterable;

    /**
     * @dataProvider providerInvalidValues
     * @param array<string, mixed>|null $parameters
     */
    public function testInvalidValues(mixed $value, string $code, string $message, array $parameters = null): void
    {
        $this->validator->validate($value, $this->getConstraint());
        $this->buildViolation($message)
            ->setCode($code)
            ->setParameters($parameters ?? [
                '{{ value }}' =>  is_string($value) ? '"' . $value . '"' : (string) $value,
            ])
            ->assertRaised();
    }

    /**
     * @return iterable<mixed>
     */
    abstract public function providerInvalidValues(): iterable;
}
