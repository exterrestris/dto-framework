<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\CompositePropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorException;
use ReflectionProperty;

trait CompositePropertyValidatorTrait
{
    /**
     * @return PropertyValidatorInterface[]
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
