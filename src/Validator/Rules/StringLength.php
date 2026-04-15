<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

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
