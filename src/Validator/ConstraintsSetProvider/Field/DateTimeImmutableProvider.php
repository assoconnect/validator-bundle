<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\DateTimeImmutableMicroSecondsType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
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
                DateTimeImmutableMicroSecondsType::NAME,
            ],
            true
        );
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Type(DateTimeImmutable::class),
        ];
    }
}
