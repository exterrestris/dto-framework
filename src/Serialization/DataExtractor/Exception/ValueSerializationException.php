<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataExtractor\Exception;

use DomainException;

class ValueSerializationException extends DomainException implements DataExtractorException
{
}
