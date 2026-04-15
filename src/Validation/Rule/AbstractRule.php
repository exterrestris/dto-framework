<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use ReflectionProperty;

abstract readonly class AbstractRule implements PropertyValidatorInterface, ValueValidatorInterface
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
