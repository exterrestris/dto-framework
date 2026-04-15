<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Exception\Internal;

use Exterrestris\DtoFramework\Exception\TypeException as TypeFrameworkException;
use InvalidArgumentException;

/**
 * @internal Must be caught and rethrown
 */
abstract class TypeException extends InvalidArgumentException implements TypeFrameworkException
{
}
