<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\AbstractValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\DtoValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ValidDto extends AbstractValidator implements PropertyValidator
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

        if (!$value instanceof DtoInterface) {
            throw new PropertyValidationException(
                $this,
                $dtoProperty,
                sprintf('Value must be an instance of %s', DtoInterface::class),
            );
        }

        try {
            $this->validateDto($value, $this->enforcePreferences);
        } catch (DtoValidationException $e) {
            throw new PropertyValidationException($this, $dtoProperty, $e->getMessage(), $e);
        }
    }
}
