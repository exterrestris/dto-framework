<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception\Internal;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exception\ValidationException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use Throwable;

/**
 * @internal Intended to simplify logic in {@link PropertyValidatorInterface} and {@link ValueValidatorInterface} instances. Must be
 *           caught and rethrown using {@link PropertyValidationException::fromValueException()} or
 *           {{@link ValueValidationException::fromValueException()}} as appropriate
 */
final class ValueException extends DomainException implements ValidationException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }
}
