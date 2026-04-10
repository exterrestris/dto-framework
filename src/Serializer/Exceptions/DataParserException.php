<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use Exterrestris\DtoFramework\Exceptions\DtoFrameworkException;

interface DataParserException extends DtoFrameworkException
{
    public function getData(): array|object|null;

    public static function withData(self $exception, array|object|null $data = null): static;
}
