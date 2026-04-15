<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Traits;

use Exterrestris\DtoFramework\Validation\Exceptions\CompositeValueValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\ValueValidator;

trait CompositeValueValidatorTrait
{
    /**
     * @return ValueValidator[]
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
