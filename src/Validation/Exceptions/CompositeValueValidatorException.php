<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\CompositeValueValidator;

/**
 * @implements ValueValidationException<CompositeValueValidator>
 */
interface CompositeValueValidatorException extends ValueValidatorException
{
    /**
     * @return ValueValidatorException[]
     */
    public function getValidatorExceptions(): array;
}
