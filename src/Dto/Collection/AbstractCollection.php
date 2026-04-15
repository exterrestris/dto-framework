<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection;

use Exterrestris\DtoFramework\Comparators\ComparatorInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\AlreadyInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\NotInCollectionException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Exceptions\Internal\TypeException;
use Exterrestris\DtoFramework\Utilities\IdenticalComparisonTrait;
use Exterrestris\DtoFramework\Utilities\CheckAcceptableTypeTrait;
use IteratorAggregate;
use Traversable;

/**
 * @template Dto of DtoInterface
 * @implements CollectionInterface<Dto>
 * @implements IteratorAggregate<int, Dto>
 */
abstract class AbstractCollection implements CollectionInterface, IteratorAggregate {
    use IdenticalComparisonTrait;
    use CheckAcceptableTypeTrait;

    /**
     * @param class-string<Dto> $dtoType
     * @throws InvalidTypeException
     */
    public function __construct(
        protected readonly string $dtoType
    ) {
        try {
            $this->verifyIsAcceptableType($this->dtoType);
        } catch (TypeException $e) {
            throw InvalidTypeException::from($e);
        }
    }

    /**
     * @param DtoInterface $item
     * @throws IncompatibleDtoException
     */
    protected function checkType(DtoInterface $item): void
    {
        if (!$item instanceof $this->dtoType) {
            throw new IncompatibleDtoException($this, $item);
        }
    }

    /**
     * @inheritDoc
     */
    public function isOfType(string $dtoType): bool
    {
        return is_a($this->dtoType, $dtoType, true);
    }

    /**
     * @inheritDoc
     */
    public function getDtoType(): string
    {
        return $this->dtoType;
    }

    /**
     * @param DtoInterface[] $items
     * @return CollectionInterface<Dto>
     */
    abstract protected function newCollection(array $items = []): CollectionInterface;

    /**
     * @inheritDoc
     */
    public function add(DtoInterface ...$items): CollectionInterface
    {
        $add = [];

        foreach ($items as $item) {
            $this->checkType($item);

            if ($this->has($item) === false) {
                $add[] = $item;
            }
        }

        return ($add) ? $this->newCollection(array_merge($this->toArray(), $add)) : $this;
    }

    /**
     * @inheritDoc
     */
    public function remove(DtoInterface ...$items): CollectionInterface
    {
        $existingItems = $this->toArray();

        foreach ($items as $item) {
            $existing = $this->has($item);

            if ($existing === false) {
                throw new NotInCollectionException($this, $item);
            }

            unset($existingItems[$existing]);
        }

        return $this->newCollection(array_values($existingItems));
    }

    /**
     * @inheritDoc
     */
    public function replace(DtoInterface $item, DtoInterface $withItem): CollectionInterface
    {
        $existing = $this->has($item);

        if ($existing === false) {
            throw new NotInCollectionException($this, $item);
        }

        if ($this->has($withItem)) {
            throw new AlreadyInCollectionException($this, $withItem);
        }

        $items = $this->toArray();
        $items[$existing] = $withItem;

        return $this->newCollection($items);
    }
    /**
     * @param DtoInterface $item
     * @return int|false The index of $item within the collection, or false if it does not exist
     */
    protected function has(DtoInterface $item): int|false
    {
        foreach ($this as $i => $existing) {
            if ($this->areIdentical($item, $existing)) {
                return $i;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function contains(DtoInterface $item): bool
    {
        return $this->has($item) !== false;
    }

    /**
     * @inheritDoc
     */
    public function clear(): CollectionInterface
    {
        return $this->newCollection();
    }

    /**
     * @inheritDoc
     */
    public function first(): ?DtoInterface
    {
        try {
            return $this->get(0);
        } catch (InvalidIndexException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @inheritDoc
     */
    public function mapToUniqueArray(callable $callback): array
    {
        return array_unique($this->mapToArray($callback));
    }

    /**
     * @inheritDoc
     */
    public function mapToArray(callable $callback): array
    {
        return array_map($callback, $this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function find(callable $callback): ?DtoInterface
    {
        try {
            return $this->filter($callback)->get(0);
        } catch (InvalidIndexException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $chunkSize): array
    {
        return array_map(function ($chunk) {
            return $this->newCollection($chunk);
        }, array_chunk($this->toArray(), $chunkSize));
    }

    /**
     * @inheritDoc
     */
    public function filter(callable $callback): CollectionInterface
    {
        return $this->newCollection(array_values(array_filter($this->toArray(), $callback)));
    }

    /**
     * @inheritDoc
     */
    public function matchAll(DtoInterface $dto, ComparatorInterface $comparator): CollectionInterface
    {
        if ($comparator->couldMatch($this->dtoType)) {
            return $this->filter($comparator->generateIsEqualToClosure($dto));
        }

        return $this->newCollection();
    }

    /**
     * @inheritDoc
     */
    public function match(DtoInterface $dto, ComparatorInterface $comparator): ?DtoInterface
    {
        if ($comparator->couldMatch($this->dtoType)) {
            return $this->find($comparator->generateIsEqualToClosure($dto));
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function split(callable $callback): array
    {
        $segments = [];

        foreach ($this as $item) {
            $segment = $callback($item);

            if (!isset($segments[$segment])) {
                $segments[$segment] = $this->newCollection();
            }

            $segments[$segment] = $segments[$segment]->add($item);
        }

        return $segments;
    }

    /**
     * @inheritDoc
     */
    public function merge(CollectionInterface ...$collections): CollectionInterface
    {
        $merged = $this;

        foreach ($collections as $collection) {
            if (!$collection->isOfType($this->dtoType)) {
                throw new IncompatibleCollectionException($this, $collection);
            }

            $merged = $merged->add(...$collection->toArray());
        }

        return $merged;
    }

    /**
     * @inheritDoc
     */
    public function diff(
        CollectionInterface $collection,
        ?ComparatorInterface $comparator = null,
    ): CollectionInterface {
        if (!$this->couldMatch($collection, $comparator)) {
            return $this;
        } elseif ($collection === $this) {
            return $this->newCollection();
        }

        $diff = array_udiff($this->toArray(), $collection->toArray(), $this->compareFn($comparator));

        return $this->newCollection(array_values($diff));
    }

    /**
     * @inheritDoc
     */
    public function intersect(
        CollectionInterface $collection,
        ?ComparatorInterface $comparator = null,
    ): CollectionInterface {
        if (!$this->couldMatch($collection, $comparator)) {
            return $this->newCollection();
        } elseif ($collection === $this) {
            return $this;
        }

        $intersect = array_uintersect($this->toArray(), $collection->toArray(), $this->compareFn($comparator));

        return $this->newCollection(array_values($intersect));
    }

    private function couldMatch(CollectionInterface $collection, ?ComparatorInterface $comparator): bool
    {
        return $collection->isOfType($this->dtoType) || ($comparator && $comparator->couldMatch($this->dtoType));
    }

    /**
     * @param ?ComparatorInterface $comparator
     * @return callable
     */
    protected function compareFn(?ComparatorInterface $comparator): callable
    {
        if ($comparator instanceof ComparatorInterface) {
            return $comparator->compare(...);
        }

        return $this->compareIdenticality(...);
    }

    /**
     * @return Traversable<int, Dto>
     */
    abstract public function getIterator(): Traversable;
}
