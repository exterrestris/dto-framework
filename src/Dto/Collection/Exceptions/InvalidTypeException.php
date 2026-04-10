<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use InvalidArgumentException;

class InvalidTypeException extends InvalidArgumentException implements CollectionException
{
}
