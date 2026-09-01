<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\DutchKvkNumberType;
use AssoConnect\ValidatorBundle\Validator\Constraints\DutchKvkNumber;

class DutchKvkNumberProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return DutchKvkNumberType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new DutchKvkNumber(),
        ];
    }
}
