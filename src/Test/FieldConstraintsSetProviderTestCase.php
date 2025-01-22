<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test;

use AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field\FieldConstraintsSetProviderInterface;
use Doctrine\ORM\Mapping\FieldMapping;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * This test should test validation violations instead of the returned set of constraints
 * But that would require using the Symfony Compound constraint which cannot be used for tests
 * @link https://github.com/symfony/symfony/issues/50205
 */
abstract class FieldConstraintsSetProviderTestCase extends TestCase
{
    abstract protected function getFactory(): FieldConstraintsSetProviderInterface;

    /**
     * @param FieldMapping $fieldMapping
     * @param Constraint[] $constraints
     * @dataProvider getConstraintsForTypeProvider
     */
    public function testGetConstraintsForType(FieldMapping $fieldMapping, array $constraints): void
    {
        $factory = $this->getFactory();
        self::assertTrue($factory->supports($fieldMapping->type));

        self::assertArrayContainsSameObjects(
            /** @phpstan-ignore-next-line */
            $factory->getConstraints($fieldMapping),
            $constraints
        );
    }

    /** @return mixed[] */
    abstract public function getConstraintsForTypeProvider(): iterable;

    /**
     * @param mixed[] $array1
     * @param mixed[] $array2
     */
    protected static function assertArrayContainsSameObjects(array $array1, array $array2, string $message = ''): void
    {
        self::assertThat($array1, new ArrayContainSameObjectsConstraint($array2), $message);
    }
}
