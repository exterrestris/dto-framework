<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNull implements PropertyValidator
{
    public function __construct()
    {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            throw new PropertyValidationException($this, $dtoProperty, 'Value is required');
        }
    }
}
