<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PreferenceValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidatorException;

abstract readonly class AbstractPreferenceValidator implements PreferenceValidator
{
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        try {
            $this->getRequirement()->validateProperty($value, $dto, $dtoProperty);
        } catch (PropertyValidatorException $e) {
            throw new PropertyValidationException($this, $e->getProperty(), $e->getMessage(), $e);
        }
    }

    public function validatePreference(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        try {
            $this->getPreference()->validateProperty($value, $dto, $dtoProperty);
        } catch (PropertyValidatorException $e) {
            throw new PreferenceValidationException($this, $e->getProperty(), $e->getMessage(), $e);
        }
    }
}
