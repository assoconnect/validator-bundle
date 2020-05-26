<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Exception;

class UnsupportedScalarFieldException extends \DomainException
{
    public function __construct(string $type, \Throwable $previous = null)
    {
        $message = sprintf('Unsupported scalar: %s', $type);
        parent::__construct($message, 0, $previous);
    }
}
