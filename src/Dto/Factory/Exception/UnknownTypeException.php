<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exception;

use Exterrestris\DtoFramework\Exception\UnknownTypeException as FrameworkUnknownTypeException;

class UnknownTypeException extends TypeException implements FrameworkUnknownTypeException
{
}
