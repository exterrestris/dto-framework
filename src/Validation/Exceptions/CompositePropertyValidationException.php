<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\CompositePropertyValidator;
use Throwable;

/**
 * @template Validator of CompositePropertyValidator
 */
class CompositePropertyValidationException extends PropertyValidationException implements CompositePropertyValidatorException
{
    /**
     * @param CompositePropertyValidator $validator
     * @param string $property
     * @param PropertyValidatorException[] $validatorExceptions
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        CompositePropertyValidator $validator,
        string $property,
        protected readonly array $validatorExceptions,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($validator, $property, $message, $previous);
    }

    /**
     * @return PropertyValidatorException[]
     */
    public function getValidatorExceptions(): array
    {
        return $this->validatorExceptions;
    }
}
