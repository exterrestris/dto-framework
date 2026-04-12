<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exceptions;

use Exterrestris\DtoFramework\Exceptions\UnsupportedTypeException as FrameworkUnsupportedTypeException;
use RuntimeException;

class UnsupportedTypeException extends RuntimeException implements FactoryException, FrameworkUnsupportedTypeException
{
}
