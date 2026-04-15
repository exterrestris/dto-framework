<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataParser;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\DataParser\Exception\DataParserException;

/**
 * @template Dto of DtoInterface
 */
interface DataParserInterface
{
    /**
     * Parses raw deserialized {@link DtoInterface}/{@link CollectionInterface} data into that {@link DtoInterface}/{@link CollectionInterface}
     *
     * Intended for use where an API client has already deserialised the data into raw objects
     *
     * @param mixed $data
     * @param class-string<Dto> $dtoType
     * @return DtoInterface|CollectionInterface<Dto>|null
     * @throws DataParserException
     */
    public function parseInto(mixed $data, string $dtoType): DtoInterface|CollectionInterface|null;

    /**
     * As {@link self::parseInto()}, but unparsable data will be skipped
     *
     * A single unparsable DTO will return NULL, a collection will return those DTOs that could be successfully
     * parsed
     *
     * @param mixed $data
     * @param class-string<Dto> $dtoType
     * @return DtoInterface|CollectionInterface<Dto>|null
     * @see self::parseInto()
     */
    public function tryParseInto(mixed $data, string $dtoType): DtoInterface|CollectionInterface|null;
}
