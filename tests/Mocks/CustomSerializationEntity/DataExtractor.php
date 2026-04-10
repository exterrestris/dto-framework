<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;

class DataExtractor implements DataExtractorInterface
{
    public function getData(DtoInterface|CollectionInterface|null $serializable, bool $excludeNoSerialize = true): array|string
    {
        return [
            $serializable->getName() => $serializable->getTitle(),
        ];
    }
}
