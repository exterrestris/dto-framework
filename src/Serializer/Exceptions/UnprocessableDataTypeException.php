<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Serializer\Config\UseDataParserPreprocessor;
use Exterrestris\DtoFramework\Serializer\DataParserPreprocessorInterface;

/**
 * Exception thrown when a {@link DataParserPreprocessorInterface} cannot process a value
 *
 * @see UseDataParserPreprocessor
 */
class UnprocessableDataTypeException extends DomainException implements
    DataParserPreprocessorException,
    UnsupportedDataTypeException
{
}
