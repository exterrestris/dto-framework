<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Traits\GetShortDtoTypeTrait;
use Throwable;

class InvalidCollectionException extends DomainException implements CollectionValidationException
{
    use GetShortDtoTypeTrait;

    /**
     * @param CollectionInterface $invalidCollection
     * @param InvalidCollectionDtoException[] $invalidDtos
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly CollectionInterface $invalidCollection,
        private readonly array $invalidDtos,
        ?Throwable $previous = null
    ) {
        $dtoType = $this->getShortType($this->invalidCollection->getDtoType());

        $message = implode("\n", array_map(
            static function (InvalidCollectionDtoException $e) use ($dtoType): string {
                return sprintf("%s %s: %s", $dtoType, $e->getIndex(), str_replace($dtoType . ' ', '', $e->getMessage()));
            },
            $this->invalidDtos
        ));

        parent::__construct($message, previous: $previous);
    }

    public function getInvalidCollection(): CollectionInterface
    {
        return $this->invalidCollection;
    }

    /**
     * @return InvalidCollectionDtoException[]
     */
    public function getInvalidDtoExceptions(): array
    {
        return $this->invalidDtos;
    }
}
