<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PreferenceValidatorException;

interface ValuePreferenceValidator extends ValueValidator
{
    public function getPreference(): ValueValidator;
    public function getRequirement(): ValueValidator;

    /**
     * @param mixed $value
     * @return void
     * @throws PreferenceValidatorException
     * @throws ConfigurationException
     */
    public function validateValuePreference(mixed $value): void;
}
