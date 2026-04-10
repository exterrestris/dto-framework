<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\FactoryInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataParserPreprocessorException;

/**
 * @template Dto of DtoInterface
 */
interface DataParserPreprocessorInterface
{
    /**
     * Preprocesses raw deserialized {@link DtoInterface} data into values usable by {@link FactoryInterface}
     *
     * Intended for use where an API client has already deserialised the data into raw objects
     *
     * @param object|array|null $data
     * @param class-string<Dto> $dtoType
     * @return object|array|null
     * @throws DataParserPreprocessorException
     * @see DataParser
     */
    public function preprocess(mixed $data, string $dtoType): object|array|null;
}
