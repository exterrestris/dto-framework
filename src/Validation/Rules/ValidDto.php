<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Traits\ValidateDtoTrait;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ValidDto extends AbstractPropertyValueValidator
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
