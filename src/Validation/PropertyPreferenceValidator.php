<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exceptions\PreferenceValidatorException;
use ReflectionProperty;

interface PropertyPreferenceValidator extends PropertyValidator
{
    public function getPreference(): PropertyValidator;
    public function getRequirement(): PropertyValidator;

    /**
     * @param ReflectionProperty $dtoProperty
     * @param DtoInterface $forDto
     * @return void
     * @throws PreferenceValidatorException
     * @throws ConfigurationException
     */
    public function validatePropertyPreference(ReflectionProperty $dtoProperty, DtoInterface $forDto): void;
}
