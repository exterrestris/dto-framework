<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validator\ValueValidator;
use Throwable;

/**
 * @template Validator of ValueValidator
 */
class ValueValidationException extends DomainException implements ValueValidatorException
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
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }

    /**
     * @return ValueValidator
     */
    public function getValidator(): ValueValidator
    {
        return $this->validator;
    }

    public static function fromValueException(ValueException $exception, ValueValidator $validator): static
    {
        return new static($validator, $exception->getMessage(), $exception->getPrevious());
    }
}
