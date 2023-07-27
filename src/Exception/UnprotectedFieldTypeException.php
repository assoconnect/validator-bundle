<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Exception;

use DomainException;

class UnprotectedFieldTypeException extends DomainException
{
    public static function becauseConstraintsNotFoundForType(string $type): self
    {
        return new self($type);
    }
}
