<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\EmailType;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\Length;

class EmailProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return EmailType::NAME === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Email(),
            new Length(['max' => ($fieldMapping->length ?? 255)]),
        ];
    }
}
