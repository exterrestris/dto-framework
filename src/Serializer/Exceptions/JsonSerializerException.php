<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use JsonException;
use RuntimeException;

abstract class JsonSerializerException extends RuntimeException implements SerializerException
{
    public static function createFromJsonException(JsonException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
