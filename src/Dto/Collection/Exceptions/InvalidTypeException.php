<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Exceptions\Internal\TypeException as InternalTypeException;
use Exterrestris\DtoFramework\Exceptions\InvalidTypeException as FrameworkInvalidTypeException;
use InvalidArgumentException;

class InvalidTypeException extends InvalidArgumentException implements
    CollectionException,
    FrameworkInvalidTypeException
{
    public function getCollection(): null
    {
        return null;
    }

    public static function from(InternalTypeException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
    }
}
