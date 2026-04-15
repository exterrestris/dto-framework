<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

interface CompositePropertyValidatorException extends PropertyValidatorException
{
    /**
     * @return PropertyValidatorException[]
     */
    public function getValidatorExceptions(): array;
}
