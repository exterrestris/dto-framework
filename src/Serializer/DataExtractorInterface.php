<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataExtractorException;

interface DataExtractorInterface
{
    /**
     * @param DtoInterface|CollectionInterface|null $serializable
     * @return array|string|null
     * @throws DataExtractorException
     */
    public function getData(DtoInterface|CollectionInterface|null $serializable): array|string|null;
}
