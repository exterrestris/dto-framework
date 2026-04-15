<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataPreprocessor\Exception;

use DomainException;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\DataPreprocessorInterface;
use Exterrestris\DtoFramework\Serialization\Exception\UnsupportedDataTypeException;
use Exterrestris\DtoFramework\Serialization\Rule\UseDataPreprocessor;

/**
 * Exception thrown when a {@link DataPreprocessorInterface} cannot process data for the specified {@link DtoInterface} type
 *
 * @see UseDataPreprocessor
 */
class UnsupportedDtoTypeException extends DomainException implements
    PreprocessorException,
    UnsupportedDataTypeException
{
}
