<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;

interface ValueValidator
{
    /**
     * @param mixed $value
     * @return void
     * @throws ValueValidatorException
     * @throws ConfigurationException
     */
    public function validateValue(mixed $value): void;
}
