<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\PropertyValidator;

/**
 * @template Validator of PropertyValidator
 */
interface PropertyValidatorException extends ValidationException
{
    /**
     * @return Validator
     */
    public function getValidator(): PropertyValidator;

    public function getProperty(): string;
}
