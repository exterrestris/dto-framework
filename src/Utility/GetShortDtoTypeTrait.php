<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;

trait GetShortDtoTypeTrait
{
    /**
     * @param class-string<DtoInterface> $dtoType
     * @return string
     */
    protected function getShortType(string $dtoType): string
    {
        return preg_replace('/^.+\\\\Dto\\\\(.+?)(?:Interface)?(@.+)?$/', '\1', $dtoType);
    }
}
