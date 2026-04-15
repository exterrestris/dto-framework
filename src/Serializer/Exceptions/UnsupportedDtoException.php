<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Serializer\Config\OverrideDataExtractor;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;

/**
 * Exception thrown when a {@link DataExtractorInterface} cannot process a {@link DtoInterface}
 *
 * @see OverrideDataExtractor
 */
class UnsupportedDtoException extends DomainException implements DataExtractorException, UnsupportedDataTypeException
{
}
