<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLength implements PropertyValidator
{
    public function __construct(
        private int $maxLength
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
                sprintf('Value must be a string of %s or fewer characters in length', $this->maxLength),
            );
        }

        if (strlen($value) > $this->maxLength) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be %s or fewer characters in length', $this->maxLength),
            );
        }
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }
}
