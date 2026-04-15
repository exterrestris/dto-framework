<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Throwable;

/**
 * @template Validator of PropertyValidator
 */
class PropertyValidatorConfigException extends DomainException implements
    PropertyValidatorException,
    ConfigurationException
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
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
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

    public static function fromConfigException(
        ConfigException $exception,
        PropertyValidator $validator,
        string $property
    ): static
    {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }

    /**
     * @param ValueValidatorException $exception
     * @param Validator $validator
     * @param string $property
     * @return static
     */
    public static function fromValueValidatorException(
        ValueValidatorException $exception,
        PropertyValidator $validator,
        string $property
    ): static {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }
}
