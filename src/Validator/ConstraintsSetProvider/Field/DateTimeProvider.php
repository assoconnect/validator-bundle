<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return in_array(
            $type,
            [
                Types::DATE_MUTABLE,
                Types::DATETIME_MUTABLE,
                Types::DATETIMETZ_MUTABLE,
                'datetimeutc',
            ],
            true
        );
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type(DateTime::class),
        ];
    }
}
