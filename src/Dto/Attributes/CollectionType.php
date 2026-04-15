<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Attributes;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\AbstractPropertyValueValidator;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueException;
use Exterrestris\DtoFramework\Validator\Exceptions\ValueValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class CollectionType extends AbstractPropertyValueValidator
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
    public function validateValue(mixed $value): void
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
            throw ValueValidationException::fromValueException($exception, $this);
        }
    }
}
