<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Symfony\Component\Validator\Constraints\Uuid;

class UuidProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return 'uuid' === $type || 'uuid_binary_ordered_time' === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Uuid(),
        ];
    }
}
