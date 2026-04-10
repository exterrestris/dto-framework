<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PreferenceValidationException;

interface PreferenceValidator extends PropertyValidator
{
    public function getPreference(): PropertyValidator;
    public function getRequirement(): PropertyValidator;

    /**
     * @param mixed $value
     * @param DtoInterface $dto
     * @param string $dtoProperty
     * @return void
     * @throws PreferenceValidationException
     */
    public function validatePreference(mixed $value, DtoInterface $dto, string $dtoProperty): void;
}
