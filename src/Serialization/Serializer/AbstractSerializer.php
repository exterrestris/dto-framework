<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;
use Exterrestris\DtoFramework\Serialization\DataParser\DataParserInterface;

abstract class AbstractSerializer implements SerializerInterface
{
    public function __construct(
        protected readonly DataExtractorInterface $dataExtractor,
        protected readonly DataParserInterface $dataParser,
    ) {
    }

    public function hash(DtoInterface|CollectionInterface $serializable): string
    {
        return sha1($this->serialize($serializable));
    }
}
