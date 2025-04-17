<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
#[\Attribute]
class FloatScale extends Constraint
{
    public const TOO_PRECISE_ERROR = '1cb54d2e-1258-490d-b2cc-91a0a21d9556';

    public string $message = 'The float precision is limited to {{ scale }} numbers.';

    public int $scale;

    /**
     * @param int|array<mixed> $scale
     * @param array<mixed> $options
     */
    public function __construct(
        $scale,
        ?array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        if (\is_array($scale)) {
            $options = array_merge($scale, $options);
        } elseif (null !== $scale) {
            $options['value'] = $scale;
        }

        parent::__construct($options, $groups, $payload);
    }

    public function getDefaultOption(): string
    {
        return 'scale';
    }

    public function getRequiredOptions(): array
    {
        return ['scale'];
    }
}
