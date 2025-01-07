<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class StringProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::STRING === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        $constraints = [new Length(['max' => $fieldMapping->length ?? 255])];

        if (isset($fieldMapping->nullable) && true === $fieldMapping->nullable) {
            $constraints[] = new NotBlank(['allowNull' => true]);
        }

        return $constraints;
    }
}
