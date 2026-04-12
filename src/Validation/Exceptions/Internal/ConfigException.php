<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions\Internal;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValidationException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use Throwable;

/**
 * @internal Intended to simplify logic in {@link PropertyValidator} and {@link ValueValidator} instances. Must be
 *           caught and rethrown using {@link PropertyValidatorConfigException::fromValueException()} or
 *           {{@link ValueValidatorConfigException::fromValueException()}} as appropriate
 */
class ConfigException extends DomainException implements ValidationException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }
}
