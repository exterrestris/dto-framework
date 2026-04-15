<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exception\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Validator\ValuePreferenceValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use Throwable;

/**
 * @template Validator of ValueValidatorInterface
 */
class ValueValidationException extends DomainException implements ValueValidatorException
{
    /**
     * @param Validator $validator
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly ValueValidatorInterface $validator,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }

    /**
     * @return Validator
     */
    public function getValidator(): ValueValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValueException $exception
     * @param Validator $validator
     * @return static
     */
    public static function fromValueException(ValueException $exception, ValueValidatorInterface $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }

    /**
     * @param ValueValidatorException $exception
     * @param ValueValidatorInterface $validator
     * @return static
     * @see ValuePreferenceValidatorInterface
     */
    public static function fromValueValidationException(
        ValueValidatorException $exception,
        ValueValidatorInterface $validator
    ): self {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }
}
