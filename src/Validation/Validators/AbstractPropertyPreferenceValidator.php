<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PreferenceValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidationPreferenceException;
use Exterrestris\DtoFramework\Validation\Exceptions\PropertyValidatorPreferenceConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationPreferenceException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorPreferenceConfigException;
use Exterrestris\DtoFramework\Validation\PropertyPreferenceValidator;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\ValuePreferenceValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;
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
            $this->getPreference()->validateValue($dtoProperty->getValue($forDto));
        } catch (ValueValidatorException $e) {
            if ($e instanceof ConfigurationException) {
                throw PropertyValidatorPreferenceConfigException::fromValueValidatorException($e, $this, $dtoProperty->getName());
            }
            throw PropertyValidationPreferenceException::fromValueValidatorException($e, $this, $dtoProperty->getName());
        }
    }

    public function validateValue(mixed $value): void
    {
        try {
            $this->getRequirement()->validateValue($value);
        } catch (ValueValidatorException $e) {
            if ($e instanceof ConfigurationException) {
                throw ValueValidatorConfigException::fromValueValidationException($e, $this);
            }

            throw ValueValidationException::fromValueValidationException($e, $this);
        }
    }

    public function validateValuePreference(mixed $value): void
    {
        try {
            $this->getPreference()->validateValue($value);
        } catch (ValueValidatorException $e) {
            if ($e instanceof ConfigurationException) {
                throw ValueValidatorPreferenceConfigException::fromValueValidationException($e, $this);
            }

            throw ValueValidationPreferenceException::fromValueValidationException($e, $this);
        }
    }
}
