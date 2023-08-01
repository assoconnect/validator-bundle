<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test;

use PHPUnit\Framework\Constraint\Constraint;

class ArrayContainSameObjectsConstraint extends Constraint
{
    /**
     * @var array<mixed>
     */
    private array $expected;

    /**
     * @param array<mixed> $expected
     */
    public function __construct(array $expected)
    {
        $this->expected = $expected;
    }

    /**
     * @param mixed $other
     */
    public function matches($other): bool
    {
        if (count($other) !== count($this->expected)) {
            return false;
        }

        foreach ($other as $key => $element) {
            if (get_class($element) !== get_class($this->expected[$key])) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return 'contain same objects';
    }
}
