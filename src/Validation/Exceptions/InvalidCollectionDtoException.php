<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\CollectionItemException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Throwable;

class InvalidCollectionDtoException extends InvalidDtoException implements CollectionItemException
{
    /**
     * @param CollectionInterface $invalidCollection
     * @param int $index
     * @param DtoInterface $invalidDto
     * @param DtoPropertyValidationException[] $validationExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly CollectionInterface $invalidCollection,
        private readonly int $index,
        DtoInterface $invalidDto,
        array $validationExceptions,
        ?Throwable $previous = null,
    ) {
        parent::__construct($invalidDto, $validationExceptions, $previous);
    }

    public function getCollection(): CollectionInterface
    {
        return $this->invalidCollection;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public static function from(InvalidDtoException $exception, CollectionInterface $forCollection, int $dtoIndex): self
    {
        return new self(
            $forCollection,
            $dtoIndex,
            $exception->getDto(),
            $exception->getValidationExceptions(),
            $exception->getPrevious()
        );
    }
}
