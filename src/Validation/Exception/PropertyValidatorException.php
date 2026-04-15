<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;

/**
 * @template Validator of PropertyValidatorInterface
 */
interface PropertyValidatorException extends ValidationException
{
    /**
     * @return Validator
     */
    public function getValidator(): PropertyValidatorInterface;

    public function getProperty(): string;
}
