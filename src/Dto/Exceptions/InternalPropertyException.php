<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\FactoryException;
use Throwable;

class InternalPropertyException extends DomainException implements DtoException, FactoryException
{
    public function __construct(?Throwable $previous = null) {
        parent::__construct('Cannot set internal property', previous: $previous);
    }
}
