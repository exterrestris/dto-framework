<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\FactoryException;
use Throwable;

abstract class AbstractDtoPropertyException extends DomainException implements DtoPropertyException, FactoryException
{
    public function __construct(
        private readonly DtoInterface $dto,
        private readonly string $property,
        string $message = "",
        ?Throwable $previous = null
    ) {
        parent::__construct($message, previous: $previous);
    }

    public function getDto(): ?DtoInterface
    {
        return $this->dto;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
