<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Throwable;

/**
 * @template Validator of PropertyValidatorInterface
 */
class PropertyValidatorConfigException extends DomainException implements
    PropertyValidatorException,
    ConfigurationException
{
    /**
     * @param PropertyValidatorInterface $validator
     * @param string $property
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly PropertyValidatorInterface $validator,
        protected readonly string $property,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }

    /**
     * @return PropertyValidatorInterface
     */
    public function getValidator(): PropertyValidatorInterface
    {
        return $this->validator;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public static function fromConfigException(
        ConfigException $exception,
        PropertyValidatorInterface $validator,
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
        PropertyValidatorInterface $validator,
        string $property
    ): static {
        return new static($validator, $property, $exception->getMessage(), $exception->getPrevious());
    }
}
