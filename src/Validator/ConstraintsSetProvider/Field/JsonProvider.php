<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;

class JsonProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::JSON === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        // TODO: Implement Symfony JSON validator
        return [];
    }
}
