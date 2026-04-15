<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer\Exception;

use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;

class JsonSerializationException extends JsonSerializerException implements SerializationException
{
    public static function createFromDataExtractorException(DataExtractorException $exception): static
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
