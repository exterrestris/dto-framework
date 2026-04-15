<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Rule\CompileArrayValuesTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsNotInList extends AbstractRule
{
    use CompileArrayValuesTrait;

    public function __construct(
        private array $disallowedValues,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$this->disallowedValues) {
            throw new ValueValidatorConfigException($this, 'No disallowed values provided');
        }

        if (in_array($value, $this->disallowedValues)) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must not be %s', $this->compileValues($this->disallowedValues)),
            );
        }
    }
}
