<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Symfony\Component\Validator\Constraints\Ulid;

class UlidProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return 'ulid' === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Ulid(),
        ];
    }
}
