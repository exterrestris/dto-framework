<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory;

use Closure;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\LazyCollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Exception\FactoryException;
use Generator;

/**
 * @template Dto of DtoInterface
 */
interface FactoryInterface
{
    /**
     * Creates an instance of the specified {@link DtoInterface} type
     *
     * @param class-string<Dto> $dtoType
     * @param array<string,mixed>|object|null $withData
     * @return DtoInterface
     * @throws FactoryException
     */
    public function create(string $dtoType, array|object|null $withData = null): DtoInterface;

    /**
     * Creates a {@link CollectionInterface} instance for the specified {@link DtoInterface} type
     *
     * @param class-string<Dto> $ofDtoType
     * @return CollectionInterface
     * @throws FactoryException
     */
    public function createCollection(string $ofDtoType): CollectionInterface;

    /**
     * Creates a {@link CollectionInterface} instance containing the specified {@link DtoInterface} instance
     *
     * @param DtoInterface ...$items
     * @return CollectionInterface
     * @throws FactoryException
     */
    public function createCollectionFor(DtoInterface ...$items): CollectionInterface;

    /**
     * @param class-string<Dto> $ofDtoType
     * @param Closure(): Generator<Dto> $bufferGeneratorFn
     * @param ?int $dtoCount
     * @return LazyCollectionInterface<Dto>
     * @throws FactoryException
     */
    public function createLazyCollection(
        string $ofDtoType,
        Closure $bufferGeneratorFn,
        ?int $dtoCount = null,
    ): LazyCollectionInterface;
}
