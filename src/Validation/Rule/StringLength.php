<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLength extends AbstractRule
{
    public function __construct(
        private int $length
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->length < 0) {
            throw new ValueValidatorConfigException($this, 'Minimum length must be greater than zero');
        }

        if (!is_string($value)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be a string exactly %s characters in length', $this->length),
            );
        }

        if (strlen($value) !== $this->length) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be exactly %s characters in length', $this->length),
            );
        }
    }
}
