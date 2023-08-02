<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\BicType;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Regex;

class BicProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return BicType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Bic(),
            new Regex('/^[0-9A-Z]+$/'),
        ];
    }
}
