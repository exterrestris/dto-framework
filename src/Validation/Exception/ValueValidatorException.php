<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;

/**
 * @template Validator of ValueValidatorInterface
 */
interface ValueValidatorException extends ValidationException
{
    /**
     * @return Validator
     */
    public function getValidator(): ValueValidatorInterface;
}
