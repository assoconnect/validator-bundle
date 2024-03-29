<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\CountryType;
use Symfony\Component\Validator\Constraints\Country;

class CountryProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return CountryType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Country(),
        ];
    }
}
