<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

interface SerializationException extends SerializerException
{
    public static function createFromDataExtractorException(DataExtractorException $exception): static;
}
