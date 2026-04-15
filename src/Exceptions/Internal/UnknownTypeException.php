<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Exceptions\Internal;

use Exterrestris\DtoFramework\Exceptions\UnknownTypeException as UnknownTypeFrameworkException;

/**
 * @internal
 */
class UnknownTypeException extends TypeException implements UnknownTypeFrameworkException
{
}
