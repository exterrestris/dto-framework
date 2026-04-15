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
 *           caught and rethrown using {@link PropertyValidatorConfigException::fromValueException()} or
 *           {{@link ValueValidatorConfigException::fromValueException()}} as appropriate
 */
final class ConfigException extends DomainException implements ValidationException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }
}
