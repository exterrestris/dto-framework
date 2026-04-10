<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\Config\UseDataParserPreprocessor;
use Exterrestris\DtoFramework\Serializer\DataParserPreprocessorInterface;

/**
 * Exception thrown when a {@link DataParserPreprocessorInterface} cannot process data for a {@link DtoInterface} type
 *
 * @see UseDataParserPreprocessor
 */
class UnsupportedDtoTypeException extends DomainException implements
    DataParserPreprocessorException,
    UnsupportedDataTypeException
{
}
