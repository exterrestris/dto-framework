<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Validation\ValueValidator;

/**
 * @template Validator of ValueValidator
 */
interface ValueValidatorException extends ValidationException
{
    /**
     * @return Validator
     */
    public function getValidator(): ValueValidator;
}
