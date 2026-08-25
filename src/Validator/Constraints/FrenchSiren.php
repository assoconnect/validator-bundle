<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraints\Luhn;

/**
 * @author Florian Guimier <florian.guimier@assoconnect.com>
 */
#[\Attribute]
class FrenchSiren extends Luhn
{
    public const INVALID_FORMAT_ERROR = '4d762774-3g50-4bd5-a6d5-b10a3299d8d3';

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
            message: $message ?? 'The value {{ value }} is not a valid SIREN number.',
            groups: $groups,
            payload: $payload,
        );
    }
}
