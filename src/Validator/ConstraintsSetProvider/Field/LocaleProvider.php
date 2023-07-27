<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\LocaleType;
use Symfony\Component\Validator\Constraints\Locale;

class LocaleProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return LocaleType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Locale(['canonicalize' => true]),
        ];
    }
}
