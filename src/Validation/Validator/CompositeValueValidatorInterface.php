<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Validation\Exception\CompositeValueValidatorException;

interface CompositeValueValidatorInterface extends ValueValidatorInterface
{
    /**
     * @return ValueValidatorInterface[]
     */
    public function getValidators(): array;

    /**
     * @param mixed $value
     * @return void
     * @throws CompositeValueValidatorException
     */
    public function validateValue(mixed $value): void;
}
