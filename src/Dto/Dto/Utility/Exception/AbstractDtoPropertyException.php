<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Utility\Exception;

use DomainException;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Exception\DtoFrameworkException;
use Throwable;

abstract class AbstractDtoPropertyException extends DomainException implements DtoFrameworkException
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
