<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\CompositePropertyValidator;

/**
 * @implements PropertyValidationException<CompositePropertyValidator>
 */
interface CompositePropertyValidatorException extends PropertyValidatorException
{
    /**
     * @return PropertyValidatorException[]
     */
    public function getValidatorExceptions(): array;
}
