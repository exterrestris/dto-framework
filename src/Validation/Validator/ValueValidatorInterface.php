<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorException;

interface ValueValidatorInterface
{
    /**
     * @param mixed $value
     * @return void
     * @throws ValueValidatorException
     * @throws ConfigurationException
     */
    public function validateValue(mixed $value): void;
}
