<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators;

use Closure;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;

interface ComparatorInterface
{
    /**
     * Compare two DTOs and return the sort order
     *
     * Intended for use in functions such as {@see array_uintersect()} and {@see usort()}
     * that require sorting comparison functions
     *
     * @param DtoInterface $a
     * @param DtoInterface $b
     * @return int Returns `-1`, `0`, or `1` if `$a` is considered to be respectively less than, equal to, or greater than `$b`
     */
    public function compare(DtoInterface $a, DtoInterface $b): int;

    /**
     * Compare two DTOs for equality
     *
     * @param DtoInterface $a
     * @param DtoInterface $b
     * @return bool
     */
    public function areEqual(DtoInterface $a, DtoInterface $b): bool;

    /**
     * Creates a closure that when called compares a DTO for equality with the DTO supplied at creation
     *
     * Intended for use in functions such as {@see array_filter()} and {@see CollectionInterface::find()}
     *
     * @param DtoInterface $compareWith
     * @return Closure(DtoInterface $dto): bool
     */
    public function generateIsEqualToClosure(DtoInterface $compareWith): Closure;

    /**
     * Check if the comparator could potentially match the given DTO type
     *
     * @param class-string<DtoInterface> $dtoType
     * @return bool Returns `false` if the comparator definitely will not match the DTO type, otherwise returns `true`
     *
     * @internal Pre-check optimization method for use by {@see CollectionInterface} methods involving a comparator
     */
    public function couldMatch(string $dtoType): bool;
}
