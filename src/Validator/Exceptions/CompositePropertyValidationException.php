<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;
use Throwable;

/**
 * @template Validator of PropertyValidator
 */
class CompositePropertyValidationException extends DomainException implements CompositePropertyValidatorException
{
    /**
     * @param PropertyValidator $validator
     * @param PropertyValidatorException[] $validatorExceptions
     * @param string $property
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly PropertyValidator $validator,
        protected readonly array $validatorExceptions,
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

    public function getValidatorExceptions(): array
    {
        return $this->validatorExceptions;
    }
}
