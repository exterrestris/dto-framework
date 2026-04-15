<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Validator\CompositeValueValidatorInterface;

/**
 * @implements ValueValidationException<CompositeValueValidatorInterface>
 */
interface CompositeValueValidatorException extends ValueValidatorException
{
    /**
     * @return ValueValidatorException[]
     */
    public function getValidatorExceptions(): array;
}
