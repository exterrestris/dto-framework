<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use OutOfBoundsException;
use ReflectionClass;
use Throwable;

class NotInCollectionException extends OutOfBoundsException implements CollectionItemException
{
    public function __construct(
        private readonly DtoInterface $dto,
        ?Throwable $previous = null
    ) {
        $reflect = new ReflectionClass($dto);

        parent::__construct(sprintf("%s not in collection", $reflect->getShortName()), 0, $previous);
    }

    public function getDto(): DtoInterface
    {
        return $this->dto;
    }
}
