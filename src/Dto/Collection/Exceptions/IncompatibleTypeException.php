<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetShortDtoTypeTrait;
use InvalidArgumentException;
use Throwable;

/**
 * @template Serializable of DtoInterface|CollectionInterface
 * @template Dto of DtoInterface
 */
abstract class IncompatibleTypeException extends InvalidArgumentException implements CollectionException
{
    use GetShortDtoTypeTrait;

    /**
     * @param Serializable $serializable
     * @param class-string<Dto> $collectionType
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly DtoInterface|CollectionInterface $serializable,
        protected readonly string $collectionType,
        $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return Serializable
     */
    public function getSerializable(): DtoInterface|CollectionInterface
    {
        return $this->serializable;
    }

    /**
     * @return class-string<Dto>
     */
    public function getCollectionType(): string
    {
        return $this->collectionType;
    }
}
