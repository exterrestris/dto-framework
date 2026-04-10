<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators;

use Closure;
use Exterrestris\DtoFramework\Dto\DtoInterface;

abstract class AbstractComparator implements ComparatorInterface
{
    public function __invoke(DtoInterface $a, DtoInterface $b): bool
    {
        return $this->areEqual($a, $b);
    }

    public function areEqual(DtoInterface $a, DtoInterface $b): bool
    {
        return $this->compare($a, $b) === 0;
    }

    public function generateIsEqualToClosure(DtoInterface $compareWith): Closure
    {
        return fn($dto) => $this->areEqual($dto, $compareWith);
    }

    public function couldMatch(string $dtoType): bool
    {
        return true;
    }
}
