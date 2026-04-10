<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMinLength implements PropertyValidator
{
    public function __construct(
        private int $minLength
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
                sprintf('Value must be a string of %s or more characters in length', $this->minLength),
            );
        }

        if (strlen($value) < $this->minLength) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be %s or more characters in length', $this->minLength),
            );
        }
    }
}
