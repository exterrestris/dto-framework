<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetShortDtoTypeTrait;
use Throwable;

/**
 * @implements IncompatibleTypeException<CollectionInterface>
 */
class IncompatibleCollectionException extends IncompatibleTypeException
{
    use GetShortDtoTypeTrait;

    /**
     * @param CollectionInterface $collection
     * @param class-string<DtoInterface> $collectionType
     * @param Throwable|null $previous
     */
    public function __construct(
        CollectionInterface $collection,
        string $collectionType,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $collection,
            $collectionType,
            sprintf(
                "Collection of %s cannot be merged with collection of %s",
                $this->getShortType($collection->getDtoType()),
                $this->getShortType($collectionType),
            ),
            $previous
        );
    }

    public function getCollection(): CollectionInterface
    {
        return $this->serializable;
    }
}
