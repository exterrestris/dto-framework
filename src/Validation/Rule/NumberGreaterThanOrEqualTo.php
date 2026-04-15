<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\Metadata\ConfigCannotBeInvalid;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class NumberGreaterThanOrEqualTo extends AbstractRule
{
    public function __construct(
        private int|float $minValue
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
                sprintf('Value must be a number greater than or equal to %s', $this->minValue),
            );
        }

        if ($value < $this->minValue) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be greater than or equal to %s', $this->minValue),
            );
        }
    }
}
