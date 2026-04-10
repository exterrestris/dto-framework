<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;

/**
 * @method InvalidDtoException getPrevious()
 */
class InvalidCollectionDtoException extends DomainException implements
    CollectionValidationException,
    DtoValidationException
{

    public function __construct(
        private readonly CollectionInterface $invalidCollection,
        private readonly int $index,
        InvalidDtoException $exception,
    ) {
        parent::__construct($exception->getMessage(), previous: $exception);
    }

    public function getInvalidDto(): DtoInterface
    {
        return $this->getPrevious()->getInvalidDto();
    }

    /**
     * @return string[]
     */
    public function getInvalidProperties(): array
    {
        return $this->getPrevious()->getInvalidProperties();
    }


    /**
     * @return array<string, DtoPropertyValidationException[]>
     */
    public function getValidationExceptions(): array
    {
        return $this->getPrevious()->getValidationExceptions();
    }

    /**
     * @return DtoPropertyValidationException[]
     */
    public function getPropertyValidationExceptions(string $property): array
    {
        return $this->getPrevious()->getPropertyValidationExceptions($property);
    }

    public function getInvalidCollection(): CollectionInterface
    {
        return $this->invalidCollection;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
