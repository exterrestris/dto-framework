<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NumberLessThan extends AbstractPropertyValueValidator
{
    public function __construct(
        private int|float $maxValue,
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
                sprintf('Value must be a number less than %s', $this->maxValue),
            );
        }

        if ($value >= $this->maxValue) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be less than to %s', $this->maxValue),
            );
        }
    }
}
