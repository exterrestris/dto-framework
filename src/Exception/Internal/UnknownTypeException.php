<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Exception\Internal;

use Exterrestris\DtoFramework\Exception\UnknownTypeException as UnknownTypeFrameworkException;

/**
 * @internal
 */
final class UnknownTypeException extends TypeException implements UnknownTypeFrameworkException
{
}
