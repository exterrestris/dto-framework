<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\Metadata\ConfigCannotBeInvalid;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class NumberLessThanOrEqualTo extends AbstractRule
{
    public function __construct(
        private int|float $maxValue
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!is_numeric($value)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be a number less than or equal to %s', $this->maxValue),
            );
        }

        if ($value > $this->maxValue) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be less than or equal to %s', $this->maxValue),
            );
        }
    }
}
