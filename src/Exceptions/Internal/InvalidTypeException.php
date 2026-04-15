<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Exceptions\Internal;

use Exterrestris\DtoFramework\Exceptions\InvalidTypeException as InvalidTypeFrameworkException;
use Throwable;

/**
 * @internal
 */
class InvalidTypeException extends TypeException implements InvalidTypeFrameworkException
{
    public function __construct(
        string $message = 'Type must be a instantiable object implementing DtoInterface',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
