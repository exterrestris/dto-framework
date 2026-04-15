<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PreferenceValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidatorException;
use ReflectionProperty;

abstract readonly class AbstractPropertyPreferenceValidator extends AbstractPropertyValueValidator implements
    PropertyPreferenceValidator,
    ValuePreferenceValidator
{
    abstract public function getPreference(): ValueValidator&PropertyValidator;
    abstract public function getRequirement(): ValueValidator&PropertyValidator;

    public function validatePropertyPreference(ReflectionProperty $dtoProperty, DtoInterface $forDto): void
    {
        try {
            $this->validateValuePreference($dtoProperty->getValue($forDto));
        } catch (ValueValidatorException $e) {
            throw PropertyValidationException::fromValueValidatorException($e, $this, $dtoProperty->getName());
        }
    }

    public function validateValue(mixed $value): void
    {
        try {
            $this->getRequirement()->validateValue($value);
        } catch (ValueValidatorException $e) {
            throw new PreferenceValidationException($this, $e->getMessage(), $e);
        }
    }

    public function validateValuePreference(mixed $value): void
    {
        try {
            $this->getPreference()->validateValue($value);
        } catch (ValueValidatorException $e) {
            throw new PreferenceValidationException($this, $e->getMessage(), $e);
        }
    }
}
