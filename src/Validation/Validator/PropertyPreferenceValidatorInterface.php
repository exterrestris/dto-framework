<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\ConfigurationException;
use Exterrestris\DtoFramework\Validation\Exception\PreferenceValidatorException;
use ReflectionProperty;

interface PropertyPreferenceValidatorInterface extends PropertyValidatorInterface
{
    public function getPreference(): PropertyValidatorInterface;
    public function getRequirement(): PropertyValidatorInterface;

    /**
     * @param ReflectionProperty $dtoProperty
     * @param DtoInterface $forDto
     * @return void
     * @throws PreferenceValidatorException
     * @throws ConfigurationException
     */
    public function validatePropertyPreference(ReflectionProperty $dtoProperty, DtoInterface $forDto): void;
}
