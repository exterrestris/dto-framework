<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Exceptions\Internal;

use Exterrestris\DtoFramework\Exceptions\TypeException as TypeFrameworkException;
use InvalidArgumentException;

/**
 * @internal Must be caught and rethrown
 */
abstract class TypeException extends InvalidArgumentException implements TypeFrameworkException
{
}
