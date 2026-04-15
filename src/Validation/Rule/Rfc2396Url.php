<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Rfc2396Url extends AbstractRule
{
    public function __construct()
    {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED | FILTER_NULL_ON_FAILURE) === null) {
            throw new ValueValidationException($this, 'Value must be a valid URL');
        }
    }
}
