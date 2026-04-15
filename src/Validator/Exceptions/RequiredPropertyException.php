<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Throwable;

class RequiredPropertyException extends DomainException implements DtoPropertyValidationException
{
    public function __construct(
        protected readonly string $property,
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($message ?: 'Value is required', previous: $previous);
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
