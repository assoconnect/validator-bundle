<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\FieldMapping;
use Symfony\Component\Validator\Constraint;

/**
 * @phpstan-import-type FieldMapping from ClassMetadataInfo
 */
interface FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool;

    /**
     * @return Constraint[]
     */
    public function getConstraints(FieldMapping $fieldMapping): array;
}
