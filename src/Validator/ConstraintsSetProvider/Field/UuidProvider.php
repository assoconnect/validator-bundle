<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Uuid;

class UuidProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return 'uuid' === $type || 'uuid_binary_ordered_time' === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Uuid(),
        ];
    }
}
