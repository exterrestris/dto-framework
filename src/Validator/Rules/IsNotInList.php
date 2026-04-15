<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\Rules\Traits\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsNotInList extends AbstractPropertyValueValidator
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

        if (in_array($value, $this->allowedValues)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must not be %s', $this->compileValues($this->allowedValues)),
            );
        }
    }
}
