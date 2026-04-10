<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidTypeException extends InvalidArgumentException implements FactoryException
{
    public function __construct(
        string $message = 'Type must be a instantiable object implementing DtoInterface',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}
