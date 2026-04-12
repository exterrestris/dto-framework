<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use InvalidArgumentException;
use Throwable;

/**
 * @template Serializable of DtoInterface|CollectionInterface
 * @template Dto of DtoInterface
 */
abstract class IncompatibleItemException extends InvalidArgumentException implements CollectionException
{

    /**
     * @param CollectionInterface $collection
     * @param Serializable $incompatibleItem
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly CollectionInterface $collection,
        protected readonly DtoInterface|CollectionInterface $incompatibleItem,
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getCollection(): ?CollectionInterface
    {
        return $this->collection;
    }

    /**
     * @return Serializable
     */
    public function getIncompatibleItem(): DtoInterface|CollectionInterface
    {
        return $this->incompatibleItem;
    }
}
