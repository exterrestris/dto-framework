<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exception;

use Exterrestris\DtoFramework\Exception\UnsupportedTypeException as FrameworkUnsupportedTypeException;
use RuntimeException;

class UnsupportedTypeException extends RuntimeException implements FactoryException, FrameworkUnsupportedTypeException
{
}
