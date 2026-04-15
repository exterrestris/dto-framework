<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Exceptions\TypeException;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ConfigException;
use Exterrestris\DtoFramework\Validation\ValuePreferenceValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use Throwable;

/**
 * @template Validator of ValueValidator
 */
class ValueValidatorConfigException extends DomainException implements ValueValidatorException, ConfigurationException
{
    /**
     * @param ValueValidator $validator
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly ValueValidator $validator,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }

    /**
     * @return ValueValidator
     */
    public function getValidator(): ValueValidator
    {
        return $this->validator;
    }

    public static function fromConfigException(ConfigException $exception, ValueValidator $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }

    public static function fromTypeException(TypeException $exception, ValueValidator $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }

    /**
     * @param ValueValidatorException $exception
     * @param ValueValidator $validator
     * @return self
     * @see ValuePreferenceValidator
     */
    public static function fromValueValidationException(
        ValueValidatorException $exception,
        ValueValidator $validator
    ): static {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }
}
