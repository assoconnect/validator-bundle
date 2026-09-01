<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\ItalianFiscalCodeType;
use AssoConnect\ValidatorBundle\Validator\Constraints\ItalianFiscalCode;

class ItalianFiscalCodeProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return ItalianFiscalCodeType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new ItalianFiscalCode(),
        ];
    }
}
