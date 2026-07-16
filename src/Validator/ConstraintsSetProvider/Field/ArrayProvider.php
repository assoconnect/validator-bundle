<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Symfony\Component\Validator\Constraints\Type;

class ArrayProvider implements FieldConstraintsSetProviderInterface
{
    // Types::ARRAY was removed in DBAL 4 but legacy columns may still use the type name
    private const TYPE_ARRAY = 'array';

    public function supports(string $type): bool
    {
        return self::TYPE_ARRAY === $type || 'simple_array' === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type('array'),
        ];
    }
}
