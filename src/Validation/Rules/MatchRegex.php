<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class MatchRegex extends AbstractPropertyValueValidator
{
    public function __construct(
        private string $pattern,
        private ?string $message = null,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (preg_match($this->pattern, $value) !== 1) {
            throw new ValueValidationException(
                $this,
                $this->message ?? sprintf('Value must match regex pattern "%s"', $this->pattern),
            );
        }
    }
}
