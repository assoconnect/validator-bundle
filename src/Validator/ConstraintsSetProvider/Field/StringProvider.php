<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webmozart\Assert\Assert;

class StringProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::STRING === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        $length = $fieldMapping['length'] ?? 255;
        Assert::positiveInteger($length);

        $constraints = [new Length(max: $length)];

        if (isset($fieldMapping['nullable']) && true === $fieldMapping['nullable']) {
            $constraints[] = new NotBlank(allowNull: true);
        }

        return $constraints;
    }
}
