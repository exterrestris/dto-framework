<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exceptions;

use Exterrestris\DtoFramework\Exceptions\UnknownTypeException as FrameworkUnknownTypeException;

class UnknownTypeException extends TypeException implements FrameworkUnknownTypeException
{
}
