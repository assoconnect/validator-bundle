<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\ConstraintsSetProvider\Field;

use Symfony\Component\Validator\Constraint;

interface FieldConstraintsSetProviderInterface
{
    public function supports(string $type): bool;

    /**
     * The field mapping is the array shape of the ORM FieldMapping object,
     * as normalized by EntityValidator before calling providers.
     *
     * @param mixed[] $fieldMapping
     * @return list<Constraint>
     */
    public function getConstraints(array $fieldMapping): array;
}
