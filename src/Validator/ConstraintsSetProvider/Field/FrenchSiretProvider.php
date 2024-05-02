<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\FrenchSiretType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;

class FrenchSiretProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return FrenchSiretType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [];
    }
}
