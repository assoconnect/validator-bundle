<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use AssoConnect\ValidatorBundle\Validator\Constraints\FloatScale;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Type;
use Webmozart\Assert\Assert;

class DecimalProvider implements FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool
    {
        return Types::DECIMAL === $type;
    }

    public function getConstraints(array $fieldMapping): array
    {
        Assert::keyExists($fieldMapping, 'precision');
        Assert::keyExists($fieldMapping, 'scale');

        return [
            new Type('float'),
            new GreaterThan(- pow(10, $fieldMapping['precision'] - $fieldMapping['scale'])),
            new LessThan(pow(10, $fieldMapping['precision'] - $fieldMapping['scale'])),
            new FloatScale($fieldMapping['scale']),
        ];
    }
}
