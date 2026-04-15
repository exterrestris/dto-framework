<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility\Exception;

use DomainException;
use Exterrestris\DtoFramework\Exception\DtoFrameworkException;

/**
 * @internal Must be caught and rethrown
 */
class ParseValueException extends DomainException implements DtoFrameworkException {
}
