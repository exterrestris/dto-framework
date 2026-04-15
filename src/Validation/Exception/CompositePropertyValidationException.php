<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Validator\CompositePropertyValidatorInterface;
use Throwable;

/**
 * @template Validator of CompositePropertyValidatorInterface
 */
class CompositePropertyValidationException extends PropertyValidationException implements CompositePropertyValidatorException
{
    /**
     * @param CompositePropertyValidatorInterface $validator
     * @param string $property
     * @param PropertyValidatorException[] $validatorExceptions
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        CompositePropertyValidatorInterface $validator,
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
