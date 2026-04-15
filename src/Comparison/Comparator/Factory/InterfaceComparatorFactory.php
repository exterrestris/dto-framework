<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparison\Comparator\Factory;

use Exterrestris\DtoFramework\Comparison\Comparator\InterfaceComparator;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;

final readonly class InterfaceComparatorFactory
{
    public function __construct(
        private DataExtractorInterface $dataExtractor,
    ) {
    }

    /**
     * @param class-string<DtoInterface> $dtoType
     */
    public function create(string $dtoType): InterfaceComparator
    {
        return new InterfaceComparator($dtoType, $this->dataExtractor);
    }
}
