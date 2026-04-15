<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;

class DataExtractor implements DataExtractorInterface
{
    public function getData(DtoInterface|CollectionInterface|null $serializable, bool $excludeNoSerialize = true): array|string
    {
        return [
            $serializable->getName() => $serializable->getTitle(),
        ];
    }
}
