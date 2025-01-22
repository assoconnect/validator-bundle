<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Type;

class ArrayProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::ARRAY === $type || Types::SIMPLE_ARRAY  === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Type('array'),
        ];
    }
}
