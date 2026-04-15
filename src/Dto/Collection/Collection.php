<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection;

use ArrayIterator;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Traversable;

/**
 * @template Dto of DtoInterface
 * @extends AbstractCollection<Dto>
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     * @param DtoInterface $items
     * @throws IncompatibleDtoException
     */
    public function __construct(
        string $dtoType,
        protected readonly array $items = []
    ) {
        parent::__construct($dtoType);

        foreach ($items as $item) {
            $this->checkType($item);
        }
    }

    /**
     * @inheritDoc
     */
    protected function newCollection(array $items = []): CollectionInterface
    {
        return new static($this->dtoType, $items);
    }

    /**
     * @inheritDoc
     */
    public function get(int $index): DtoInterface
    {
        if (isset($this->items[$index])) {
            return $this->items[$index];
        }

        throw new InvalidIndexException($this, $index);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback, ?string $newDtoType = null): CollectionInterface
    {
        return new static($newDtoType ?? $this->getDtoType(), array_map($callback, $this->items));
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
