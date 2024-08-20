<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;

class StringProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::STRING === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        $constraints = [
            new Length(['max' => $fieldMapping['length'] ?? 255]),
            new NoSuspiciousCharacters(),
        ];

        if (isset($fieldMapping['nullable']) && true === $fieldMapping['nullable']) {
            $constraints[] = new NotBlank(['allowNull' => true]);
        }

        return $constraints;
    }
}
