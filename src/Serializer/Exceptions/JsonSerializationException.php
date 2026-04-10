<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

class JsonSerializationException extends JsonSerializerException implements SerializationException
{
    public static function createFromDataExtractorException(DataExtractorException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
