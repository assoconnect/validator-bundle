<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\PhoneMobileType;
use AssoConnect\ValidatorBundle\Validator\Constraints\PhoneMobile;

class PhoneMobileProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return PhoneMobileType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new PhoneMobile(),
        ];
    }
}
