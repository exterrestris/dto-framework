<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer\Exception;

use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;

interface SerializationException extends SerializerException {
    public static function createFromDataExtractorException(DataExtractorException $exception): static;
}
