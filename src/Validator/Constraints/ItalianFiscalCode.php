<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraints\Luhn;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class ItalianFiscalCode extends Luhn
{
    public const string WRONG_FORMAT_ERROR = '3c063d70-b74a-4f4f-9401-096bf71ab47b';
    public const string MESSAGE = 'The Italian Fiscal Code {{ value }} is not valid.';

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
            message: $message ?? self::MESSAGE,
            groups: $groups,
            payload: $payload,
        );
    }
}
