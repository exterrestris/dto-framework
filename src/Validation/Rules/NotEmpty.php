<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rules\Traits\EmptyValueTrait;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmpty extends AbstractPropertyValueValidator
{
    use EmptyValueTrait;

    public function validateValue(mixed $value): void
    {
        if ($this->isEmpty($value)) {
            throw new ValueValidationException($this, 'Value must not be empty');
        }
    }
}
