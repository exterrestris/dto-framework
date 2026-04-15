<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
use ReflectionProperty;

abstract readonly class AbstractPropertyValueValidator implements PropertyValidator, ValueValidator
{
    public function validateProperty(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        try {
            $this->validateValue($dtoProperty->getValue($forDto));
        } catch (ValueValidatorConfigException $e) {
            throw PropertyValidatorConfigException::fromValueValidatorException($e, $this, $dtoProperty->getName());
        } catch (ValueValidatorException $e) {
            throw PropertyValidationException::fromValueValidatorException($e, $this, $dtoProperty->getName());
        }
    }
}
