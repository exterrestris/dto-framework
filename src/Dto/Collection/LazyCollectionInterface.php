<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Traversable;

/**
 * 'Lazy-loaded' DTO collection interface
 *
 * @inheritDoc
 *
 * @template Dto of DtoInterface
 * @implements Traversable<int, Dto>
 */
interface LazyCollectionInterface extends CollectionInterface
{
    /**
     * Indicates whether the number of entities in the collection is known, or must be calculated
     *
     * @return bool
     */
    public function isCountKnown(): bool;
}
