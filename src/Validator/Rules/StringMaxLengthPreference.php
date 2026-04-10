<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPreferenceValidator;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLengthPreference extends AbstractPreferenceValidator
{
    public function __construct(
        private int $preferredMaxLength,
        private int $maxLength,
    ) {
    }

    public function getPreference(): PropertyValidator
    {
        return new StringMaxLength($this->preferredMaxLength);
    }

    public function getRequirement(): PropertyValidator
    {
        return new StringMaxLength($this->maxLength);
    }
}
