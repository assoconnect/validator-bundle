<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Exception;

class UnsupportedFieldException extends \DomainException
{
    public function __construct(string $className, string $field, \Throwable $previous = null)
    {
        $message = sprintf('Unsupported field: %s::$%s', $className, $field);
        parent::__construct($message, 0, $previous);
    }
}
