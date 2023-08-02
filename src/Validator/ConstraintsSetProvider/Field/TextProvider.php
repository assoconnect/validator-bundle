<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Length;

class TextProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::TEXT === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Length([
                'max' => $fieldMapping['length'] ?? 65535,
                'charset' => '8bit',
            ]),
        ];
    }
}
