<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\MoneyType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Money;

class MoneyProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return MoneyType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Money(),
            new FloatScale($fieldMapping['scale'] ?? MoneyType::DEFAULT_SCALE),
        ];
    }
}
