<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use DomainException;
use Exterrestris\DtoFramework\Exception\TypeException;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\Validator\ValuePreferenceValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use Throwable;

/**
 * @template Validator of ValueValidatorInterface
 */
class ValueValidatorConfigException extends DomainException implements ValueValidatorException, ConfigurationException
{
    /**
     * @param ValueValidatorInterface $validator
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly ValueValidatorInterface $validator,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }

    /**
     * @return ValueValidatorInterface
     */
    public function getValidator(): ValueValidatorInterface
    {
        return $this->validator;
    }

    public static function fromConfigException(ConfigException $exception, ValueValidatorInterface $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }

    public static function fromTypeException(TypeException $exception, ValueValidatorInterface $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }

    /**
     * @param ValueValidatorException $exception
     * @param ValueValidatorInterface $validator
     * @return self
     * @see ValuePreferenceValidatorInterface
     */
    public static function fromValueValidationException(
        ValueValidatorException $exception,
        ValueValidatorInterface $validator
    ): static {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }
}
