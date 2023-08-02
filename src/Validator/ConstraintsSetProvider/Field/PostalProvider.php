<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\PostalType;

class PostalProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return PostalType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        // TODO: To implement
        return [];
    }
}
