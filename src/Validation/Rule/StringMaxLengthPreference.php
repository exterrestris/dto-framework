<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Validator\PropertyValidatorInterface;
use Exterrestris\DtoFramework\Validation\Validator\ValueValidatorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLengthPreference extends AbstractPreferenceRule
{
    public function __construct(
        private int $preferredMaxLength,
        private int $maxLength,
    ) {
    }

    public function getPreference(): ValueValidatorInterface&PropertyValidatorInterface
    {
        return new StringMaxLength($this->preferredMaxLength);
    }

    public function getRequirement(): ValueValidatorInterface&PropertyValidatorInterface
    {
        return new StringMaxLength($this->maxLength);
    }
}
