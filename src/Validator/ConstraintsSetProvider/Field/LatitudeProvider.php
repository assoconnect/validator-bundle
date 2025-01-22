<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LatitudeType;
use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use AssoConnect\ValidatorBundle\Validator\Constraints\Latitude;
use Doctrine\ORM\Mapping\FieldMapping;

class LatitudeProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return LatitudeType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Latitude(),
            new FloatScale($fieldMapping->scale ?? LatitudeType::DEFAULT_SCALE),
        ];
    }
}
