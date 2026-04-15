<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Validation\Exceptions\CompositeValueValidatorException;

interface CompositeValueValidator extends ValueValidator
{
    /**
     * @return ValueValidator[]
     */
    public function getValidators(): array;

    /**
     * @param mixed $value
     * @return void
     * @throws CompositeValueValidatorException
     */
    public function validateValue(mixed $value): void;
}
