<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\UsSocialSecurityNumberType;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;

class UsSocialSecurityNumberProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return UsSocialSecurityNumberType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new UsSocialSecurityNumber(),
        ];
    }
}
