<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataPreprocessor;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\FactoryInterface;
use Exterrestris\DtoFramework\Serialization\DataParser\DataParser;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\Exception\PreprocessorException;

/**
 * @template Dto of DtoInterface
 */
interface DataPreprocessorInterface
{
    /**
     * Preprocesses raw deserialized {@link DtoInterface} data into values usable by {@link FactoryInterface}
     *
     * Intended for use where an API client has already deserialised the data into raw objects
     *
     * @param object|array|null $data
     * @param class-string<Dto> $dtoType
     * @return object|array|null
     * @throws PreprocessorException
     * @see DataParser
     */
    public function preprocess(mixed $data, string $dtoType): object|array|null;
}
