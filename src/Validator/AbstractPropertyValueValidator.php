<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidatorException;
use ReflectionProperty;

abstract readonly class AbstractPropertyValueValidator implements PropertyValidator, ValueValidator
{
    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        try {
            $this->validateValue($dtoProperty->getValue($forDto));
        } catch (ValueValidatorException $e) {
            throw PropertyValidationException::fromValueValidatorException($e, $this, $dtoProperty->getName());
        }
    }
}
