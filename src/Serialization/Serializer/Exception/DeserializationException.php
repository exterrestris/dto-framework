<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer\Exception;

use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserException;

interface DeserializationException extends SerializerException
{
    public static function createFromDataParserException(DataParserException $exception): static;
}
