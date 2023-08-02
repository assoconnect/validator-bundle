<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\FrenchRnaType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FrenchRna;

class FrenchRnaProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return FrenchRnaType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new FrenchRna(),
        ];
    }
}
