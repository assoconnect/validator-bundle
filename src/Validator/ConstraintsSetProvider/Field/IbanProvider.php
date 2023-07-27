<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\IbanType;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\Regex;

class IbanProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return IbanType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Iban(),
            // This regex is required for stricter validation
            // because the Symfony IBAN validator accepts spaces
            new Regex('/^[0-9A-Z]+$/'),
        ];
    }
}
