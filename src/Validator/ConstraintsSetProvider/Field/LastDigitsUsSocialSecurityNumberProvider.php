<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LastDigitsUsSocialSecurityNumberType;
use AssoConnect\ValidatorBundle\Validator\Constraints\LastDigitsUsSocialSecurityNumber;
use Doctrine\ORM\Mapping\FieldMapping;

class LastDigitsUsSocialSecurityNumberProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return LastDigitsUsSocialSecurityNumberType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new LastDigitsUsSocialSecurityNumber(),
        ];
    }
}
