<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

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
