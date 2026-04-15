<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLengthBetween extends AbstractPropertyValueValidator
{
    public function __construct(
        private int $minLength,
        private int $maxLength,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!is_string($value)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be a string between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }

        if (strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }
    }
}
