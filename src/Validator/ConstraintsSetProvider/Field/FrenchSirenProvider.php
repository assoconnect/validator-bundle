<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\FrenchSirenType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchSiren;

class FrenchSirenProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return FrenchSirenType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new FrenchSiren(),
        ];
    }
}
