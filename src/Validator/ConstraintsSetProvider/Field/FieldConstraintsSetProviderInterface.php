<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Symfony\Component\Validator\Constraint;

interface FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool;

    /**
     * The field mapping is always the ORM 2 array shape: on ORM 3, EntityValidator
     * normalizes the FieldMapping object back to an array before calling providers.
     *
     * @param mixed[] $fieldMapping
     * @return list<Constraint>
     */
    public function getConstraints(array $fieldMapping): array;
}
