<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataPreprocessor\Exception;

use DomainException;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\DataPreprocessorInterface;
use Exterrestris\DtoFramework\Serialization\Rule\UseDataPreprocessor;

/**
 * Exception thrown when a {@link DataPreprocessorInterface} cannot process the supplied data
 *
 * @see UseDataPreprocessor
 */
class DataPreprocessingException extends DomainException implements PreprocessorException
{
}
