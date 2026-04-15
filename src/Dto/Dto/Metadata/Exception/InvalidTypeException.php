<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Metadata\Exception;

use Exterrestris\DtoFramework\Exception\Internal\TypeException as InternalTypeException;
use Exterrestris\DtoFramework\Exception\InvalidTypeException as FrameworkInvalidTypeExceptionAlias;
use InvalidArgumentException;

class InvalidTypeException extends InvalidArgumentException implements FrameworkInvalidTypeExceptionAlias
{
    public static function from(InternalTypeException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
    }
}
