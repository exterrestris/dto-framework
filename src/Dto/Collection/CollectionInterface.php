<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection;

use Countable;
use Exterrestris\DtoFramework\Comparison\Comparator\ComparatorInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exception\AlreadyInCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\IncompatibleCollectionException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\Collection\Exception\NotInCollectionException;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Traversable;

/**
 * Base DTO collection interface
 *
 * Extending {@link AbstractCollection} rather than implementing this interface directly is recommended.
 *
 * Collections implementing this interface:
 * - SHOULD be immutable, i.e. methods that alter the collection SHOULD return a new collection instance rather than
 *   modify the collection in place
 * - SHOULD contain a single type, i.e. creating a collection of {@link DtoInterface} rather than a more specific type
 *   SHOULD NOT be allowed
 *
 * @template Dto of DtoInterface
 * @implements Traversable<int, Dto>
 */
interface CollectionInterface extends Countable, Traversable
{
    /**
     * @return class-string<Dto>
     */
    public function getDtoType(): string;

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return bool
     */
    public function isOfType(string $dtoType): bool;

    /**
     * @param DtoInterface ...$items
     * @return CollectionInterface<Dto>
     * @throws IncompatibleDtoException
     */
    public function add(DtoInterface ...$items): CollectionInterface;

    /**
     * @param DtoInterface ...$items
     * @return CollectionInterface<Dto>
     * @throws NotInCollectionException
     */
    public function remove(DtoInterface ...$items): CollectionInterface;

    /**
     * @param DtoInterface $item
     * @return bool
     */
    public function contains(DtoInterface $item): bool;

    /**
     * @param DtoInterface $item
     * @param DtoInterface $withItem
     * @return CollectionInterface<Dto>
     * @throws NotInCollectionException
     * @throws AlreadyInCollectionException
     */
    public function replace(DtoInterface $item, DtoInterface $withItem): CollectionInterface;

    /**
     * @return CollectionInterface<Dto>
     */
    public function clear(): CollectionInterface;

    /**
     * @param int $index
     * @return DtoInterface
     * @throws InvalidIndexException
     */
    public function get(int $index): DtoInterface;

    /**
     * @return ?DtoInterface
     */
    public function first(): ?DtoInterface;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Converts the collection to an array
     *
     * @return DtoInterface[]
     */
    public function toArray(): array;

    /**
     * Splits the collection into chunks
     *
     * @return CollectionInterface<Dto>[] Array of chunked collections
     */
    public function chunk(int $chunkSize): array;

    /**
     * Apply a callback to each item in the collection, returning a new collection
     *
     * @template MappedDto of DtoInterface|Dto
     * @param callable(Dto):MappedDto $callback
     * @param class-string<MappedDto>|null $newDtoType The DTO type for the new collection. If NULL retains the type of the current collection
     * @return CollectionInterface<MappedDto>
     */
    public function map(callable $callback, ?string $newDtoType = null): CollectionInterface;

    /**
     * @param callable(Dto):mixed $callback
     * @return array
     */
    public function mapToArray(callable $callback): array;

    /**
     * @param callable(Dto):mixed $callback
     * @return array
     */
    public function mapToUniqueArray(callable $callback): array;

    /**
     * Filters the collection using a callback, returning a new collection
     *
     * @param callable(Dto):bool $callback
     * @return CollectionInterface<Dto>
     */
    public function filter(callable $callback): CollectionInterface;

    /**
     * Finds and returns the first item in the collection matching the callback
     *
     * @param callable(Dto):bool $callback
     * @return ?DtoInterface
     */
    public function find(callable $callback): ?DtoInterface;

    /**
     * Filters the collection by matching against the specified item using the supplied comparator
     *
     * @param DtoInterface $dto
     * @param ComparatorInterface $comparator
     * @return CollectionInterface<Dto>
     */
    public function matchAll(DtoInterface $dto, ComparatorInterface $comparator): CollectionInterface;

    /**
     * Finds and returns the first item in the collection that matches the specified item using the supplied comparator
     *
     * @param DtoInterface $dto
     * @param ComparatorInterface $comparator
     * @return ?DtoInterface
     */
    public function match(DtoInterface $dto, ComparatorInterface $comparator): ?DtoInterface;

    /**
     * Splits the collection into segments
     *
     * @param callable(Dto):string $callback Function to determine the segment that each item should belong to
     * @return array<string,CollectionInterface<Dto>> Array of segmented collections, with the segment identifiers as keys
     */
    public function split(callable $callback): array;

    /**
     * Merge another collection into the collection
     *
     * @param CollectionInterface<Dto> ...$collections
     * @return CollectionInterface<Dto>
     * @throws IncompatibleCollectionException
     */
    public function merge(CollectionInterface ...$collections): CollectionInterface;

    /**
     * Return the items in the collection that are not in the specified collection
     *
     * @param CollectionInterface $collection
     * @param ?ComparatorInterface $comparator
     * @return CollectionInterface<Dto>
     */
    public function diff(
        CollectionInterface $collection,
        ?ComparatorInterface $comparator = null,
    ): CollectionInterface;

    /**
     * Return the items in the collection that are also in the specified collection
     *
     * @param CollectionInterface $collection
     * @param ?ComparatorInterface $comparator
     * @return CollectionInterface<Dto>
     */
    public function intersect(
        CollectionInterface $collection,
        ?ComparatorInterface $comparator = null,
    ): CollectionInterface;
}
