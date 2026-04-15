<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLength extends AbstractRule
{
    public function __construct(
        private int $maxLength
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->maxLength < 0) {
            throw new ValueValidatorConfigException($this, 'Minimum length must be greater than zero');
        }

        if (!is_string($value)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be a string of %s or fewer characters in length', $this->maxLength),
            );
        }

        if (strlen($value) > $this->maxLength) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be %s or fewer characters in length', $this->maxLength),
            );
        }
    }
}
