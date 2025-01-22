<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Ulid;

class UlidProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return 'ulid' === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Ulid(),
        ];
    }
}
