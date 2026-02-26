<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\BelgianEnterpriseNumberType;
use AssoConnect\ValidatorBundle\Validator\Constraints\BelgianEnterpriseNumber;

class BelgianEnterpriseNumberProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return BelgianEnterpriseNumberType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new BelgianEnterpriseNumber(),
        ];
    }
}
