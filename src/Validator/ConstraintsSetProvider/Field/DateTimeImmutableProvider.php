<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\DateTimeMicroSecondsType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Type;

class DateTimeImmutableProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return in_array(
            $type,
            [
                Types::DATE_IMMUTABLE,
                Types::DATETIME_IMMUTABLE,
                Types::DATETIMETZ_IMMUTABLE,
                'datetimeutc_immutable',
                DateTimeMicroSecondsType::NAME,
            ],
            true
        );
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type(DateTimeImmutable::class),
        ];
    }
}
