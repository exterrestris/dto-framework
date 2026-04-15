<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\DtoValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\Traits\ValidateDtoTrait;

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
        } catch (DtoValidationException $e) {
            throw new ValueValidationException($this, $e->getMessage(), $e);
        }
    }
}
