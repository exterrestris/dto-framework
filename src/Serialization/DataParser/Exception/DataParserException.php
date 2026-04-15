<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataParser\Exception;

use Exterrestris\DtoFramework\Serialization\Exception\SerializationException;

interface DataParserException extends SerializationException
{
    public function getData(): array|object|null;

    public static function withData(self $exception, array|object|null $data = null): static;
}
