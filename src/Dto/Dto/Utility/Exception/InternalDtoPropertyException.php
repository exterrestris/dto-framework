<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Utility\Exception;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Throwable;

final class InternalDtoPropertyException extends AbstractDtoPropertyException
{
    public function __construct(
        DtoInterface $dto,
        string $property,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $dto,
            $property,
            sprintf('Cannot set internal property "%s"', $property),
            $previous
        );
    }
}
