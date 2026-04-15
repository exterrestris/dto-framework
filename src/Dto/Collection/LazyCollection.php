<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection;

use CachingIterator;
use CallbackFilterIterator;
use Closure;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\IncompatibleDtoException;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\InvalidIndexException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\FactoryInterface;
use Generator;
use Iterator;
use Traversable;

/**
 * @template Dto of DtoInterface
 * @implements LazyCollectionInterface<Dto>
 * @extends AbstractCollection<Dto>
 */
class LazyCollection extends AbstractCollection implements LazyCollectionInterface
{
    /**
     * @inheritDoc
     * @param FactoryInterface $factory
     * @param Closure(): Generator<int, Dto, void, void> $bufferGeneratorFn
     * @throws IncompatibleDtoException
     */
    public function __construct(
        string $dtoType,
        private readonly FactoryInterface $factory,
        private readonly Closure $bufferGeneratorFn,
        private ?int $dtoCount = null,
    ) {
        parent::__construct($dtoType);
    }

    protected function newCollection(array $items = []): CollectionInterface
    {
        $new = $this->factory->createCollection($this->dtoType);

        return ($items) ? $new->add(...$items) : $new;
    }

    /**
     * @inheritDoc
     */
    public function get(int $index): DtoInterface
    {
        if ($index < 0 || ($this->dtoCount !== null && $index >= $this->dtoCount)) {
            throw new InvalidIndexException($this, $index);
        }

        foreach ($this as $i => $item) {
            if ($i === $index) {
                return $item;
            }
        }

        throw new InvalidIndexException($this, $index);
    }

    public function count(): int
    {
        if ($this->dtoCount === null) {
            $this->dtoCount = 0;

            foreach ($this as $ignored) {
                $this->dtoCount++;
            }
        }

        return $this->dtoCount;
    }

    /**
     * @inheritDoc
     */
    public function isCountKnown(): bool
    {
        return $this->dtoCount !== null;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback, ?string $newDtoType = null): CollectionInterface
    {
        return $this->factory->createCollection($newDtoType ?? $this->getDtoType())
            ->add(...array_map($callback, $this->toArray()));
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        $generator = ($this->bufferGeneratorFn)();

        if ($this->dtoCount === null) {
            return $this->populateCountFromTraversable($generator);
        }

        return $generator;
    }

    /**
     * Caches the DTO count after the first complete iteration
     *
     * Uses a {@link CallbackFilterIterator} and {@link CachingIterator} to determine the DTO count
     *
     * @param Iterator $traversable
     * @return Iterator
     */
    private function populateCountFromTraversable(Iterator $traversable): Iterator
    {
        $count = 0;

        return new CallbackFilterIterator(
            new CachingIterator($traversable, CachingIterator::TOSTRING_USE_KEY),
            function ($current, $key, CachingIterator $iterator) use (&$count): bool {
                $count++;

                if (!$iterator->hasNext()) {
                    $this->dtoCount = $count;
                }

                return true;
            }
        );
    }
}
