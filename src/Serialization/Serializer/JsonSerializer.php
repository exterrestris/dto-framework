<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserException;
use Exterrestris\DtoFramework\Serialization\Serializer\Exception\JsonDeserializationException;
use Exterrestris\DtoFramework\Serialization\Serializer\Exception\JsonSerializationException;
use JsonException;

class JsonSerializer extends AbstractSerializer implements JsonSerializerInterface
{
    public function serialize(DtoInterface|CollectionInterface|null $serializable): string
    {
        try {
            return json_encode($this->dataExtractor->getData($serializable), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException $e) {
            throw JsonSerializationException::createFromJsonException($e);
        } catch (DataExtractorException $e) {
            throw JsonSerializationException::createFromDataExtractorException($e);
        }
    }

    public function deserialize(string $data, string $dtoType): DtoInterface|CollectionInterface|null
    {
        try {
            return $this->dataParser->parseInto(json_decode($data, flags: JSON_THROW_ON_ERROR), $dtoType);
        } catch (JsonException $e) {
            throw JsonDeserializationException::createFromJsonException($e);
        } catch (DataParserException $e) {
            throw JsonDeserializationException::createFromDataParserException($e);
        }
    }
}
