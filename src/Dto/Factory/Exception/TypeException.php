<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exception;

use Exterrestris\DtoFramework\Exception\Internal\TypeException as InternalTypeException;
use Exterrestris\DtoFramework\Exception\TypeException as FrameworkTypeException;
use InvalidArgumentException;

/**
 * @internal Must be caught and rethrown
 */
abstract class TypeException extends InvalidArgumentException implements FrameworkTypeException, FactoryException
{
    public static function from(InternalTypeException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
    }
}
