<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataExtractorException;

/**
 * Compare DTOs using all their serializable properties
 *
 * @see EqualComparator for comparision including non-serializable properties
 */
final class EquivalentComparator extends AbstractComparator
{
    public function __construct(private readonly DataExtractorInterface $dataExtractor)
    {
    }

    public function compare(DtoInterface $a, DtoInterface $b): int
    {
        try {
            return $this->dataExtractor->getData($a) <=> $this->dataExtractor->getData($b);
        } catch (DataExtractorException) {
            return -1;
        }
    }
}
