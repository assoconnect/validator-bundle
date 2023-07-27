<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\TimezoneType;
use Symfony\Component\Validator\Constraints\Timezone;

class TimezoneProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return TimezoneType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Timezone(),
        ];
    }
}
