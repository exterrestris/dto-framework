<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Utility\Exception;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Throwable;

final class NoSuchDtoPropertyException extends AbstractDtoPropertyException
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
