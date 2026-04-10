<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use Exterrestris\DtoFramework\Validator\PropertyValidator;

interface PropertyValidatorException extends DtoPropertyValidationException
{
    public function getValidator(): PropertyValidator;
}
