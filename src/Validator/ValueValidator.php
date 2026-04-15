<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidatorException;

interface ValueValidator
{
    /**
     * @param mixed $value
     * @return void
     * @throws ValueValidatorException
     */
    public function validateValue(mixed $value): void;
}
