<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Rule\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsInList extends AbstractRule
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

        if (!$this->allowedValues) {
            throw new ValueValidatorConfigException($this, 'No allowed values provided');
        }

        if (!in_array($value, $this->allowedValues)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be %s', $this->compileValues($this->allowedValues)),
            );
        }
    }
}
