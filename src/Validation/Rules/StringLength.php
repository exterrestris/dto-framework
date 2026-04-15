<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLength extends AbstractPropertyValueValidator
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
