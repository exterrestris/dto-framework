<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\PropertyValidator;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyPreferenceValidator;
use Exterrestris\DtoFramework\Validation\ValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLengthPreference extends AbstractPropertyPreferenceValidator
{
    public function __construct(
        private int $preferredMaxLength,
        private int $maxLength,
    ) {
    }

    public function getPreference(): ValueValidator&PropertyValidator
    {
        return new StringMaxLength($this->preferredMaxLength);
    }

    public function getRequirement(): ValueValidator&PropertyValidator
    {
        return new StringMaxLength($this->maxLength);
    }
}
