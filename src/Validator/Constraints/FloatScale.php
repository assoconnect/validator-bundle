<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Sylvain Fabre <sylvain.fabre@assoconnect.com>
 */
class FloatScale extends Constraint
{
    public const TOO_PRECISE_ERROR = '1cb54d2e-1258-490d-b2cc-91a0a21d9556';

    public string $message = 'The float precision is limited to {{ scale }} numbers.';

    public int $scale;

    public function getDefaultOption(): string
    {
        return 'scale';
    }

    public function getRequiredOptions(): array
    {
        return ['scale'];
    }
}
