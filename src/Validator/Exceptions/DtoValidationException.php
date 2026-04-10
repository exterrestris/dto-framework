<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\DtoException;

interface DtoValidationException extends DtoException, ValidationException
{
    public function getInvalidDto(): DtoInterface;
}
