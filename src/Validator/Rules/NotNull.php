<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNull extends AbstractPropertyValueValidator
{
    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            throw new ValueValidationException($this, 'Value is required');
        }
    }
}
