<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataExtractor\Exception;

use DomainException;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;
use Exterrestris\DtoFramework\Serialization\Exception\UnsupportedDataTypeException;
use Exterrestris\DtoFramework\Serialization\Rule\UseDataExtractor;

/**
 * Exception thrown when a {@link DataExtractorInterface} cannot process a {@link DtoInterface}
 *
 * @see UseDataExtractor
 */
class UnsupportedDtoException extends DomainException implements DataExtractorException, UnsupportedDataTypeException
{
}
