<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMinLength extends AbstractPropertyValueValidator
{
    public function __construct(
        private int $minLength
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
                sprintf('Value must be a string of %s or more characters in length', $this->minLength),
            );
        }

        if (strlen($value) < $this->minLength) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be %s or more characters in length', $this->minLength),
            );
        }
    }
}
