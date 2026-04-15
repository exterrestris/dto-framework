<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidCollectionException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Validators\Metadata\ConfigCannotBeInvalid;
use Exterrestris\DtoFramework\Validation\Traits\ValidateCollectionTrait;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
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
        } catch (InvalidCollectionException $e) {
            throw new ValueValidationException($this, $e->getMessage(), $e);
        }
    }
}
