<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\PropertyPreferenceValidator;
use Exterrestris\DtoFramework\Validation\PropertyValidator;

/**
 * @extends PropertyValidationException<PropertyPreferenceValidator>
 */
class PropertyValidationPreferenceException extends PropertyValidationException implements PreferenceValidatorException
{
    public static function fromValueValidatorException(
        ValueValidatorException $exception,
        PropertyValidator $validator,
        string $property
    ): static {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }

    public static function fromValueException(
        ValueException $exception,
        PropertyValidator $validator,
        string $property
    ): static
    {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }
}
