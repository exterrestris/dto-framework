<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyPreferenceValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;

/**
 * @extends PropertyValidationException<PropertyPreferenceValidatorInterface>
 */
class PropertyValidationPreferenceException extends PropertyValidationException implements PreferenceValidatorException
{
    public static function fromValueValidatorException(
        ValueValidatorException $exception,
        PropertyValidatorInterface $validator,
        string $property
    ): static {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }

    public static function fromValueException(
        ValueException $exception,
        PropertyValidatorInterface $validator,
        string $property
    ): static
    {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }
}
