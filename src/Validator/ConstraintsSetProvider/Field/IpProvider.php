<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\IpType;
use Symfony\Component\Validator\Constraints\Ip;

class IpProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return IpType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        return [
            new Ip(['version' => 'all']),
        ];
    }
}
