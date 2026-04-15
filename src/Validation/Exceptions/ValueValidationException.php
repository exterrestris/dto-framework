<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use Throwable;

/**
 * @template Validator of ValueValidator
 */
class ValueValidationException extends DomainException implements ValueValidatorException
{
    /**
     * @param Validator $validator
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly ValueValidator $validator,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }

    /**
     * @return Validator
     */
    public function getValidator(): ValueValidator
    {
        return $this->validator;
    }

    /**
     * @param ValueException $exception
     * @param Validator $validator
     * @return static
     */
    public static function fromValueException(ValueException $exception, ValueValidator $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }
}
