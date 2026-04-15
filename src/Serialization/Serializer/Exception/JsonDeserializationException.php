<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer\Exception;

use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserException;

class JsonDeserializationException extends JsonSerializerException implements DeserializationException
{
    public static function createFromDataParserException(DataParserException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
