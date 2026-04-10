<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use DomainException;

class ValueSerializationException extends DomainException implements DataExtractorException
{
}
