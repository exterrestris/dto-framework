<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\CollectionException;
use Exterrestris\DtoFramework\Utilities\GetShortDtoTypeTrait;
use Throwable;

class InvalidCollectionException extends DomainException implements ItemValidatorException, CollectionException
{
    use GetShortDtoTypeTrait;

    /**
     * @param CollectionInterface $invalidCollection
     * @param InvalidCollectionDtoException[] $invalidDtoExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly CollectionInterface $invalidCollection,
        private readonly array $invalidDtoExceptions,
        ?Throwable $previous = null
    ) {
        $dtoType = $this->getShortType($this->invalidCollection->getDtoType());

        $message = implode("\n", array_map(
            static function (InvalidCollectionDtoException $e) use ($dtoType): string {
                return sprintf("%s %s: %s", $dtoType, $e->getIndex(), str_replace($dtoType . ' ', '', $e->getMessage()));
            },
            $this->invalidDtoExceptions
        ));

        parent::__construct($message, previous: $previous);
    }

    public function getCollection(): CollectionInterface
    {
        return $this->invalidCollection;
    }

    /**
     * @return InvalidCollectionDtoException[]
     */
    public function getInvalidDtoExceptions(): array
    {
        return $this->invalidDtoExceptions;
    }
}
