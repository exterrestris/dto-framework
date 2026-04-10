<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

interface DeserializationException extends SerializerException
{
    public static function createFromDataParserException(DataParserException $exception): static;
}
