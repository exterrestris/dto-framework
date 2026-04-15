<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparison\Comparator;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;

/**
 * Compare DTOs using all their properties (i.e. including non-serializable properties)
 *
 * @see EquivalentComparator for comparision ignoring non-serializable properties
 */
final class EqualComparator extends AbstractComparator
{
    public function __construct(private readonly DataExtractorInterface $dataExtractor)
    {
    }

    public function compare(DtoInterface $a, DtoInterface $b): int
    {
        try {
            return $this->dataExtractor->getData($a, false) <=> $this->dataExtractor->getData($b, false);
        } catch (DataExtractorException) {
            return -1;
        }
    }
}
