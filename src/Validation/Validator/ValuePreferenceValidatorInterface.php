<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\PreferenceValidatorException;

interface ValuePreferenceValidatorInterface extends ValueValidatorInterface
{
    public function getPreference(): ValueValidatorInterface;
    public function getRequirement(): ValueValidatorInterface;

    /**
     * @param mixed $value
     * @return void
     * @throws PreferenceValidatorException
     * @throws ConfigurationException
     */
    public function validateValuePreference(mixed $value): void;
}
