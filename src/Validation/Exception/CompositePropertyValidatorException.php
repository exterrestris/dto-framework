<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Validator\CompositePropertyValidatorInterface;

/**
 * @implements PropertyValidationException<CompositePropertyValidatorInterface>
 */
interface CompositePropertyValidatorException extends PropertyValidatorException
{
    /**
     * @return PropertyValidatorException[]
     */
    public function getValidatorExceptions(): array;
}
