<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use Throwable;

/**
 * @internal Intended to simplify logic in {@link PropertyValidator} and {@link ValueValidator} instances. Must be
 *           caught and rethrown using {@link PropertyValidationException::fromValueException()} or
 *           {{@link ValueValidationException::fromValueException()}} as appropriate
 */
class ValueException extends DomainException implements ValidationException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }
}
