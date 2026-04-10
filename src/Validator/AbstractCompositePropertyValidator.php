<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\CompositePropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;

abstract readonly class AbstractCompositePropertyValidator implements CompositePropertyValidator
{
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        $exceptions = [];

        foreach ($this->getValidators() as $validator) {
            try {
                $validator->validateProperty($value, $dto, $dtoProperty);
            } catch (PropertyValidatorException $e) {
                $exceptions[] = $e;
            }
        }

        if ($exceptions) {
            throw new CompositePropertyValidationException($this, $exceptions, $dtoProperty);
        }
    }
}
