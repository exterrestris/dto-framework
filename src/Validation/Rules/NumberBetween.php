<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validation\Validators\Metadata\ConfigCannotBeInvalid;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class NumberBetween extends AbstractPropertyValueValidator
{
    private int|float $minValue;
    private int|float $maxValue;

    public function __construct(
        int|float $minValue,
        int|float $maxValue,
    ) {
        $this->minValue = min($minValue, $maxValue);
        $this->maxValue = max($minValue, $maxValue);
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
