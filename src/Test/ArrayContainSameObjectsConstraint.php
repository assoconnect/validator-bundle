<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Test;

use PHPUnit\Framework\Constraint\Constraint;

class ArrayContainSameObjectsConstraint extends Constraint
{
    /**
     * @param mixed[] $expected
     */
    public function __construct(private readonly array $expected)
    {
    }

    public function matches(mixed $other): bool
    {
        if (!is_array($other) || count($other) !== count($this->expected)) {
            return false;
        }

        foreach ($other as $key => $element) {
            if ($element::class !== $this->expected[$key]::class) {
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
