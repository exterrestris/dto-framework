<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Throwable;

/**
 * @template Validator of PropertyValidator
 */
class PropertyValidationException extends DomainException implements PropertyValidatorException
{
    /**
     * @param PropertyValidator $validator
     * @param string $property
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly PropertyValidator $validator,
        protected readonly string $property,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }

    /**
     * @return PropertyValidator
     */
    public function getValidator(): PropertyValidator
    {
        return $this->validator;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public static function fromValueValidatorException(
        ValueValidatorException $exception,
        PropertyValidator $validator,
        string $property
    ): static {
        return new static($validator, $property, $exception->getMessage(), $exception);
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
