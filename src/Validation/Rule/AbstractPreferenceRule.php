<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidationPreferenceException;
use Exterrestris\DtoFramework\Validation\Exception\PropertyValidatorPreferenceConfigException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationPreferenceException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorPreferenceConfigException;
use Exterrestris\DtoFramework\Validation\Validator\PropertyPreferenceValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValuePreferenceValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;
use ReflectionProperty;

abstract readonly class AbstractPreferenceRule extends AbstractRule implements
    PropertyPreferenceValidatorInterface,
    ValuePreferenceValidatorInterface
{
    abstract public function getPreference(): ValueValidatorInterface&PropertyValidatorInterface;
    abstract public function getRequirement(): ValueValidatorInterface&PropertyValidatorInterface;

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
