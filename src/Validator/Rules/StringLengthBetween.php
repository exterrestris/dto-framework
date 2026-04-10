<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLengthBetween implements PropertyValidator
{
    public function __construct(
        private int $minLength,
        private int $maxLength,
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            return;
        }

        if (!is_string($value)) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be a string between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }

        if (strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }
    }
}
