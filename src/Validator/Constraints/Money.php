<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
#[\Attribute]
class Money extends Constraint
{
    public const MAX = 100000000.0;

    public float $min = 0.0;
    public float $max = self::MAX;

    public function __construct(
        float $min = 0.0,
        float $max = self::MAX,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(groups: $groups, payload: $payload);
        $this->min = $min;
        $this->max = $max;
    }
}
