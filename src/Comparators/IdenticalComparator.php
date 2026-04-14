<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Utilities\IdenticalComparisonTrait;

/**
 * Compare DTOs using their instances (i.e. match if (object) === (object))
 */
final class IdenticalComparator extends AbstractComparator
{
    use IdenticalComparisonTrait;

    public function compare(DtoInterface $a, DtoInterface $b): int
    {
        return $this->compareIdenticality($a, $b);
    }
}
