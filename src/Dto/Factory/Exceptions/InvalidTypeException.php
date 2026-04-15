<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exceptions;

use Exterrestris\DtoFramework\Exceptions\InvalidTypeException as FrameworkInvalidTypeException;

class InvalidTypeException extends TypeException implements FrameworkInvalidTypeException
{
}
