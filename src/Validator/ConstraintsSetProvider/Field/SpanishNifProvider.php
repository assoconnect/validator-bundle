<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\SpanishNifType;
use AssoConnect\ValidatorBundle\Validator\Constraints\SpanishNif;

class SpanishNifProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return SpanishNifType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new SpanishNif(),
        ];
    }
}
