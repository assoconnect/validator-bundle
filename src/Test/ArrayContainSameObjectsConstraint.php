<?php

namespace AssoConnect\ValidatorBundle\Test;

use PHPUnit\Framework\Constraint\Constraint;

class ArrayContainSameObjectsConstraint extends Constraint
{
    /**
     * @var array
     */
    private $expected;

    public function __construct(array $expected)
    {
        $this->expected = $expected;
    }

    public function matches($other): bool
    {
        if (count($other) != count($this->expected)) {
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
