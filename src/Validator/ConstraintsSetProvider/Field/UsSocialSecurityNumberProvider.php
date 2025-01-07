<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\UsSocialSecurityNumberType;
use AssoConnect\ValidatorBundle\Validator\Constraints\UsSocialSecurityNumber;
use Doctrine\ORM\Mapping\FieldMapping;

class UsSocialSecurityNumberProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return UsSocialSecurityNumberType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new UsSocialSecurityNumber(),
        ];
    }
}
