<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Exception;

class UnsupportedAssociationFieldException extends \DomainException
{
    public function __construct(string $className, string $field, string $type, \Throwable $previous = null)
    {
        $message = sprintf('Unsupported association type "%s" for %s::$%s', $type, $className, $field);
        parent::__construct($message, 0, $previous);
    }
}
