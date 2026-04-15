<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetShortDtoTypeTrait;
use Throwable;

/**
 * @implements IncompatibleTypeException<DtoInterface>
 */
class IncompatibleDtoException extends IncompatibleTypeException implements CollectionItemException
{
    use GetShortDtoTypeTrait;

    /**
     * @param DtoInterface $dto
     * @param class-string<DtoInterface> $collectionType
     * @param Throwable|null $previous
     */
    public function __construct(
        DtoInterface $dto,
        string $collectionType,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $dto,
            $collectionType,
            sprintf(
                "%s cannot be added to collection of %s",
                $this->getShortType($dto::class),
                $this->getShortType($collectionType),
            ),
            $previous
        );
    }

    public function getDto(): DtoInterface
    {
        return $this->serializable;
    }
}
