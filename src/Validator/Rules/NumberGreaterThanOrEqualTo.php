<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NumberGreaterThanOrEqualTo implements PropertyValidator
{
    public function __construct(
        private int|float $minValue
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            return;
        }

        if (!is_numeric($value)) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be a number greater than or equal to %s', $this->minValue),
            );
        }

        if ($value < $this->minValue) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be greater than or equal to %s', $this->minValue),
            );
        }
    }
}
