<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;

class DecimalProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::DECIMAL === $type;
    }

    public function getConstraints(FieldMapping $fieldMapping): array
    {
        return [
            new Type('float'),
            new GreaterThan(- pow(10, $fieldMapping->precision - $fieldMapping->scale)),
            new LessThan(pow(10, $fieldMapping->precision - $fieldMapping->scale)),
            new FloatScale($fieldMapping->scale),
        ];
    }
}
