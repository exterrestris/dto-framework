<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NumberBetween extends AbstractPropertyValueValidator
{
    public function __construct(
        private int|float $minValue,
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
                sprintf('Value must be a number between %s and %s', $this->minValue, $this->maxValue),
            );
        }

        if ($value < $this->minValue || $value > $this->maxValue) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be between %s and %s', $this->minValue, $this->maxValue),
            );
        }
    }
}
