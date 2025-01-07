<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\PhoneType;
use AssoConnect\ValidatorBundle\Validator\Constraints\Phone;
use Doctrine\ORM\Mapping\FieldMapping;

class PhoneProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return PhoneType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Phone(),
        ];
    }
}
