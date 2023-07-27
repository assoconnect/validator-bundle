<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\AmountAsBigintType;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\AmountType;
use Money\Money as MoneyObject;
use Symfony\Component\Validator\Constraints\Type;

class AmountProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return AmountType::NAME === $type || AmountAsBigintType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type(MoneyObject::class),
        ];
    }
}
