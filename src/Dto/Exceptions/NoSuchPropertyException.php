<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Throwable;

class NoSuchPropertyException extends AbstractDtoPropertyException
{
    public function __construct(
        DtoInterface $dto,
        string $property,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $dto,
            $property,
            sprintf('Property "%s" does not exist', $property),
            $previous
        );
    }
}
