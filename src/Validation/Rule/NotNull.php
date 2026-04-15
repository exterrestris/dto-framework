<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNull extends AbstractRule
{
    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            throw new ValueValidationException($this, 'Value is required');
        }
    }
}
