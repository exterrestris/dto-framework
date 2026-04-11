<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\Rules\Traits\EmptyValueTrait;

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
