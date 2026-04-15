<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Serializer;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serialization\Serializer\Exception\DeserializationException;
use Exterrestris\DtoFramework\Serialization\Serializer\Exception\SerializerException;

/**
 * @template Dto of DtoInterface
 */
interface SerializerInterface
{
    /**
     * Converts a {@link DtoInterface}/{@link CollectionInterface} into a string representation of itself
     *
     * @param DtoInterface|CollectionInterface|null $serializable
     * @return string
     * @throws SerializerException
     */
    public function serialize(DtoInterface|CollectionInterface|null $serializable): string;

    /**
     * Generates a hash of a {@link DtoInterface}/{@link CollectionInterface}
     *
     * @param DtoInterface|CollectionInterface $serializable
     * @return string
     * @throws SerializerException
     */
    public function hash(DtoInterface|CollectionInterface $serializable): string;

    /**
     * Converts a string representation of a {@link DtoInterface}/{@link CollectionInterface} into
     * that {@link DtoInterface}/{@link CollectionInterface}
     *
     * @param string $data
     * @param class-string<Dto> $dtoType
     * @return DtoInterface|CollectionInterface<Dto>|null
     * @throws DeserializationException
     */
    public function deserialize(string $data, string $dtoType): DtoInterface|CollectionInterface|null;
}
