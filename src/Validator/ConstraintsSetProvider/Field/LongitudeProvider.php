<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LongitudeType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Longitude;
use Doctrine\ORM\Mapping\FieldMapping;

class LongitudeProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return LongitudeType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Longitude(),
            new FloatScale($fieldMapping->scale ?? LongitudeType::DEFAULT_SCALE),
        ];
    }
}
