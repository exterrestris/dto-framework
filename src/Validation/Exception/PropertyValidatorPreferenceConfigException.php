<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;

class PropertyValidatorPreferenceConfigException extends PropertyValidatorConfigException implements PreferenceValidatorException
{
    public static function fromValueException(
        ValueException $exception,
        PropertyValidatorInterface $validator,
        string $property
    ): static
    {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }
}
