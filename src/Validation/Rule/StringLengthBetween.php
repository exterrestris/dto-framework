<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLengthBetween extends AbstractRule
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

        if ($this->minLength < 0) {
            throw new ValueValidatorConfigException($this, 'Minimum length must be greater than zero');
        }

        if ($this->maxLength < 0) {
            throw new ValueValidatorConfigException($this, 'Maximum length must be greater than zero');
        }

        if ($this->minLength > $this->maxLength) {
            throw new ValueValidatorConfigException($this, 'Maximum length must be greater than or equal to minimum length');
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
