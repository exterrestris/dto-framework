<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class MatchRegex extends AbstractRule
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

        if (!$this->pattern) {
            throw new ValueValidatorConfigException($this, 'A valid regex pattern is required');
        }

        match (@preg_match($this->pattern, $value)) {
            false => throw new ValueValidatorConfigException($this, 'A valid regex pattern is required'),
            0 => throw new ValueValidationException(
                $this,
                $this->message ?? sprintf('Value must match regex pattern "%s"', $this->pattern),
            ),
            default => null,
        };
    }
}
