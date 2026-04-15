<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataExtractor;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;

interface DataExtractorInterface
{
    /**
     * @param DtoInterface|CollectionInterface|null $serializable
     * @param bool $excludeNoSerialize
     * @return array|string|null
     * @throws DataExtractorException
     */
    public function getData(
        DtoInterface|CollectionInterface|null $serializable,
        bool $excludeNoSerialize = true
    ): array|string|null;
}
