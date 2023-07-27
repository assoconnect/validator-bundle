<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Type;

class BooleanProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::BOOLEAN === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Type('bool'),
        ];
    }
}
