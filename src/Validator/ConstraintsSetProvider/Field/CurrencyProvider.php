<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\CurrencyType;
use Money\Currency as CurrencyObject;
use Symfony\Component\Validator\Constraints\Currency as CurrencyConstraint;
use Symfony\Component\Validator\Constraints\Type;

class CurrencyProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return CurrencyType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new CurrencyConstraint(),
            new Type(CurrencyObject::class),
        ];
    }
}
