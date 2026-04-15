<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exception;

use DomainException;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\AbstractDtoPropertyException as DtoPropertyUtilityException;
use Throwable;

abstract class AbstractDtoPropertyException extends DomainException implements FactoryException
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

    public static function from(DtoPropertyUtilityException $e): static
    {
        throw new static($e->getDto(), $e->getProperty(), $e->getMessage(), $e->getPrevious());
    }
}
