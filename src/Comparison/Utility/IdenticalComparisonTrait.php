<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparison\Utility;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;

trait IdenticalComparisonTrait
{
    /**
     * Compare two entities for identicality and return the sort order
     *
     * Intended for use in functions such as {@see array_uintersect()} and {@see usort()}
     * that require sorting comparison functions
     *
     * @param DtoInterface $a
     * @param DtoInterface $b
     * @return int Return -1, 0, or 1 if $a is considered to be respectively less than, equal to, or greater than $b
     * @see ComparatorInterface::compare()
     */
    protected function compareIdenticality(DtoInterface $a, DtoInterface $b): int
    {
        return min(max(strcmp(spl_object_hash($a), spl_object_hash($b)), -1), 1);
    }

    protected function areIdentical(DtoInterface $a, DtoInterface $b): bool
    {
        return $this->compareIdenticality($a, $b) === 0;
    }
}
