<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NumberGreaterThan extends AbstractPropertyValueValidator
{
    public function __construct(
        private int|float $minValue,
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
                sprintf('Value must be a number greater than %s', $this->minValue),
            );
        }

        if ($value <= $this->minValue) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be greater than %s', $this->minValue),
            );
        }
    }
}
