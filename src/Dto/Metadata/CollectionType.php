<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Metadata;

use Attribute;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Metadata\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Exceptions\Internal\TypeException;
use Exterrestris\DtoFramework\Traits\CheckAcceptableTypeTrait;
use Exterrestris\DtoFramework\Validation\Exceptions\Internal\ValueException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidationException;
use Exterrestris\DtoFramework\Validation\Exceptions\ValueValidatorConfigException;
use Exterrestris\DtoFramework\Validation\Validators\AbstractPropertyValueValidator;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class CollectionType extends AbstractPropertyValueValidator
{
    use CheckAcceptableTypeTrait;

    /**
     * @param class-string<DtoInterface> $dtoType
     */
    public function __construct(
        private string $dtoType,
    ) {
    }

    public function getDtoType(): string
    {
        try {
            $this->verifyIsAcceptableType($this->dtoType);
        } catch (TypeException $e) {
            throw InvalidTypeException::from($e);
        }

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

            $this->verifyIsAcceptableType($this->dtoType);

            if (!$value instanceof CollectionInterface) {
                throw new ValueException(sprintf('Value must be an instance of %s', CollectionInterface::class));
            }

            if (!$value->isOfType($this->getDtoType())) {
                throw new ValueException(sprintf('Collection must be of type %s', $this->getDtoType()));
            }
        } catch (TypeException $exception) {
            throw ValueValidatorConfigException::fromTypeException($exception, $this);
        } catch (ValueException $exception) {
            throw ValueValidationException::fromValueException($exception, $this);
        }
    }
}
