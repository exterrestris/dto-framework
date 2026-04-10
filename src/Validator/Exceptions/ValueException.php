<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValidationException as BaseValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Throwable;

/**
 * @internal Intended to simplify logic in {@link PropertyValidator} instances.
 * Must be caught and rethrown using {@link PropertyValidationException::createFromValueException()}
 */
class ValueException extends DomainException implements BaseValidationException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }
}
