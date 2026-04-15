<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use DomainException;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\DtoPropertyException;
use Throwable;

abstract class DtoPropertyValidationException extends DomainException implements
    DtoPropertyException,
    ItemValidatorException
{
    public function __construct(
        private readonly DtoInterface $dto,
        private readonly string $property,
        ?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct(
            $message ?? sprintf('Property "%s" is invalid', $this->property),
            previous: $previous
        );
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
