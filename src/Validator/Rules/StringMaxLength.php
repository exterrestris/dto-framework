<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLength extends AbstractPropertyValueValidator
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

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }
}
