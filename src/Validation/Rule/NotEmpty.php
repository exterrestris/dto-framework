<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\EmptyValueTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmpty extends AbstractRule
{
    use EmptyValueTrait;

    public function validateValue(mixed $value): void
    {
        if ($this->isEmpty($value)) {
            throw new ValueValidationException($this, 'Value must not be empty');
        }
    }
}
