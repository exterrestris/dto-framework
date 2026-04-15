<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exception;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use OutOfBoundsException;
use Throwable;

class InvalidIndexException extends OutOfBoundsException implements CollectionException
{
    public function __construct(
        private readonly CollectionInterface $collection,
        int $index,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf('Index %s does not exist in collection', $index), previous: $previous);
    }

    public function getCollection(): ?CollectionInterface
    {
        return $this->collection;
    }
}
