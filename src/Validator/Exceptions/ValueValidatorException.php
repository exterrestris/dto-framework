<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use Exterrestris\DtoFramework\Validator\ValueValidator;

interface ValueValidatorException extends ValidationException
{
    public function getValidator(): ValueValidator;
}
