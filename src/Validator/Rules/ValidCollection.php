<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\CollectionValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validator\Traits\ValidateCollectionTrait;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ValidCollection extends AbstractPropertyValueValidator
{
    use ValidateCollectionTrait;

    public function __construct(
        private bool $enforcePreferences = false,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof CollectionInterface) {
            throw new ValueValidationException(
                $this,
                sprintf('Value must be an instance of %s', CollectionInterface::class),
            );
        }

        try {
            $this->validateCollection($value, $this->enforcePreferences);
        } catch (CollectionValidationException $e) {
            throw new ValueValidationException($this, $e->getMessage(), $e);
        }
    }
}
