<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule;

use Attribute;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exception\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exception\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Rule\Metadata\ConfigCannotBeInvalid;
use Exterrestris\DtoFramework\Validation\Validator\ValidateDtoTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class ValidDto extends AbstractRule
{
    use ValidateDtoTrait;

    public function __construct(
        private bool $enforcePreferences = false,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof DtoInterface) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be an instance of %s', DtoInterface::class),
            );
        }

        try {
            $this->validateDto($value, $this->enforcePreferences);
        } catch (InvalidDtoException $e) {
            throw new ValueValidationException($this, $e->getMessage(), $e);
        }
    }
}
