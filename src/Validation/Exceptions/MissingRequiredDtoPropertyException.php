<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Throwable;

class MissingRequiredDtoPropertyException extends DtoPropertyValidationException
{
    public function __construct(
        DtoInterface $dto,
        string $property,
        ?string $message = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $dto,
            $property,
            $message ?? sprintf('Value is required for property "%s"', $property),
            $previous
        );
    }
}
