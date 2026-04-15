<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Validation\Exception\CompositeValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorException;

trait CompositeValueValidatorTrait
{
    /**
     * @return ValueValidatorInterface[]
     */
    abstract public function getValidators(): array;

    public function validateValue(mixed $value): void
    {
        $exceptions = [];

        foreach ($this->getValidators() as $validator) {
            try {
                $validator->validateValue($value);
            } catch (ValueValidatorException $e) {
                $exceptions[] = $e;
            }
        }

        if ($exceptions) {
            throw new CompositeValueValidationException($this, $exceptions);
        }
    }
}
