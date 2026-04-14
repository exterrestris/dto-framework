<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Utilities\GetShortDtoTypeTrait;
use Throwable;

/**
 * @extends IncompatibleItemException<CollectionInterface>
 */
class IncompatibleCollectionException extends IncompatibleItemException
{
    use GetShortDtoTypeTrait;

    public function __construct(
        CollectionInterface $collection,
        CollectionInterface $incompatibleItem,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $collection,
            $incompatibleItem,
            sprintf(
                "Collection of %s cannot be merged with collection of %s",
                $this->getShortType($collection->getDtoType()),
                $this->getShortType($incompatibleItem->getDtoType()),
            ),
            $previous
        );
    }
}
