<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators\Exceptions;

use Exterrestris\DtoFramework\Exceptions\Internal\TypeException as InternalTypeException;
use Exterrestris\DtoFramework\Exceptions\InvalidTypeException as FrameworkInvalidTypeException;
use InvalidArgumentException;

class InvalidTypeException extends InvalidArgumentException implements
    ComparatorException,
    FrameworkInvalidTypeException
{
    public static function from(InternalTypeException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
    }
}
