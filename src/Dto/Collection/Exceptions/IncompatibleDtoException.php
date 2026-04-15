<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Utilities\GetShortDtoTypeTrait;
use Throwable;

/**
 * @extends IncompatibleItemException<DtoInterface>
 */
class IncompatibleDtoException extends IncompatibleItemException implements CollectionItemException
{
    use GetShortDtoTypeTrait;

    public function __construct(
        CollectionInterface $collection,
        DtoInterface $dto,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $collection,
            $dto,
            sprintf(
                "%s cannot be added to collection of %s",
                $this->getShortType($dto::class),
                $this->getShortType($collection->getDtoType()),
            ),
            $previous
        );
    }

    public function getDto(): DtoInterface
    {
        return $this->getIncompatibleItem();
    }
}
