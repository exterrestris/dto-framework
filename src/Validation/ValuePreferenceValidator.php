<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Validation\Exceptions\PreferenceValidationException;

interface ValuePreferenceValidator extends ValueValidator
{
    public function getPreference(): ValueValidator;
    public function getRequirement(): ValueValidator;

    /**
     * @param mixed $value
     * @return void
     * @throws PreferenceValidationException
     */
    public function validateValuePreference(mixed $value): void;
}
