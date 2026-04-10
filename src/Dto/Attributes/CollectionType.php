<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Attributes;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\PropertyValidationException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\PropertyValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class CollectionType implements PropertyValidator
{
    /**
     * @param class-string<DtoInterface> $dtoType
     */
    public function __construct(
        private string $dtoType,
    ) {
    }

    public function getDtoType(): string
    {
        return $this->dtoType;
    }

    /**
     * @inheritDoc
     */
    public function validateProperty(mixed $value, DtoInterface $dto, string $dtoProperty): void
    {
        try {
            if ($value === null) {
                return;
            }

            if (!$value instanceof CollectionInterface) {
                throw new ValueException(sprintf('Value must be an instance of %s', CollectionInterface::class));
            }

            if (!$value->isOfType($this->getDtoType())) {
                throw new ValueException(sprintf('Collection must be of type %s', $this->getDtoType()));
            }
        } catch (ValueException $exception) {
            throw PropertyValidationException::createFromValueException($exception, $this, $dtoProperty);
        }
    }
}
