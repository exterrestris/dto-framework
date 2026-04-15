<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

class JsonDeserializationException extends JsonSerializerException implements DeserializationException
{
    public static function createFromDataParserException(DataParserException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
