<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\PhoneLandlineType;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneLandline;

class PhoneLandlineProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return PhoneLandlineType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new PhoneLandline(),
        ];
    }
}
