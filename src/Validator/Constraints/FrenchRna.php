<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Florian Guimier <florian.guimier@assoconnect.com>
 */
#[\Attribute]
class FrenchRna extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'd125c480-3efd-42dd-9a59-6058fddd4fe4';

    public string $message = 'The value {{ value }} is not a valid RNA identifier.';

    public function __construct(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
