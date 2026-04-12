<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rules\Traits\CompileArrayValuesTrait;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsInList extends AbstractPropertyValueValidator
{
    use CompileArrayValuesTrait;

    public function __construct(
        private array $allowedValues,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!in_array($value, $this->allowedValues)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be %s', $this->compileValues($this->allowedValues)),
            );
        }
    }
}
