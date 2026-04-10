<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\AbstractValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\CollectionValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ValidCollection extends AbstractValidator implements PropertyValidator
{
    public function __construct(
        private bool $enforcePreferences = false,
    ) {
    }

    /**
     * @param mixed $value
     * @param DtoInterface $dto
     * @param string $dtoProperty
     * @inheritDoc
     */
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof CollectionInterface) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be an instance of %s', CollectionInterface::class),
            );
        }

        try {
            $this->validateCollection($value, $this->enforcePreferences);
        } catch (CollectionValidationException $e) {
            throw new PropertyValidationException($this, $dtoProperty, $e->getMessage(), $e);
        }
    }
}
