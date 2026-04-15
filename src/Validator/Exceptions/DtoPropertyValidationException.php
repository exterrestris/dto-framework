<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use Exterrestris\DtoFramework\Dto\Exceptions\DtoException;

interface DtoPropertyValidationException extends DtoException, ValidationException
{
    public function getProperty(): string;
}
