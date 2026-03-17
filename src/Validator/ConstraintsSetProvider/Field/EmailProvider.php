<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\DoctrineTypesBundle\Doctrine\DBAL\Types\EmailType;
use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Webmozart\Assert\Assert;

class EmailProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return EmailType::NAME === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        $length = $fieldMapping['length'] ?? 255;
        Assert::positiveInteger($length);

        return [
            new Email(),
            new Length(max: $length),
        ];
    }
}
