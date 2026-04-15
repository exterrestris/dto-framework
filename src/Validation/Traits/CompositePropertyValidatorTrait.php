<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Traits;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\CompositePropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use ReflectionProperty;

trait CompositePropertyValidatorTrait
{
    /**
     * @return PropertyValidator[]
     */
    abstract public function getValidators(): array;

    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        $exceptions = [];

        foreach ($this->getValidators() as $validator) {
            try {
                $validator->validateProperty($dtoProperty, $forDto);
            } catch (PropertyValidatorException $e) {
                $exceptions[] = $e;
            }
        }

        if ($exceptions) {
            throw new CompositePropertyValidationException($this, $dtoProperty->getName(), $exceptions);
        }
    }
}
