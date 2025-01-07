<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\AbsolutePercentValueBundle\Object\AbsolutePercentValue;
use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\AbsolutePercentValueType;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Type;

class AbsolutePercentValueProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return AbsolutePercentValueType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Type(AbsolutePercentValue::class),
        ];
    }
}
