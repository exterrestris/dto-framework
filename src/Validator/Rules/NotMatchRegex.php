<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotMatchRegex implements PropertyValidator
{
    public function __construct(
        private string $pattern,
        private ?string $message = null,
    ) {
    }

    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            return;
        }

        if (preg_match($this->pattern, $value) === 1) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                $this->message ?? sprintf('Value must not match regex pattern "%s"', $this->pattern),
            );
        }
    }
}
