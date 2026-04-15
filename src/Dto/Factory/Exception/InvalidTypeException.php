<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exception;

use Exterrestris\DtoFramework\Exception\InvalidTypeException as FrameworkInvalidTypeException;

class InvalidTypeException extends TypeException implements FrameworkInvalidTypeException
{
}
