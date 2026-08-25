<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraints\Luhn;

/**
 * @author Guillaume Wambre <guillaume.wambre@assoconnect.com>
 */
#[\Attribute]
class FrenchSiret extends Luhn
{
    public const INVALID_FORMAT_ERROR = 'cbe06561-776e-45c2-b33c-a73141746d43';

    /**
     * @param array<string>|null $groups
     */
    #[HasNamedArguments]
    public function __construct(
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(
            message: $message ?? 'The value {{ value }} is not a valid SIRET number.',
            groups: $groups,
            payload: $payload,
        );
    }
}
