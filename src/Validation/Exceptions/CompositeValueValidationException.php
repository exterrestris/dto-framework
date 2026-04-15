<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\CompositeValueValidator;
use Throwable;

/**
 * @extends ValueValidationException<CompositeValueValidator>
 */
class CompositeValueValidationException extends ValueValidationException implements CompositeValueValidatorException
{
    /**
     * @param CompositeValueValidator $validator
     * @param ValueValidatorException[] $validatorExceptions
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        $validator,
        protected readonly array $validatorExceptions,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($validator, $message, $previous);
    }

    /**
     * @return ValueValidatorException[]
     */
    public function getValidatorExceptions(): array
    {
        return $this->validatorExceptions;
    }
}
