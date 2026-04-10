<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators\Factory;

use Exterrestris\DtoFramework\Comparators\InterfaceComparator;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;

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
