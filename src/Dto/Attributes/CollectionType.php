<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Attributes;

use Attribute;
use Exterrestris\DtoFramework\Dto\DtoInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class CollectionType
{
    /**
     * @param class-string<DtoInterface> $dtoType
     */
    public function __construct(
        private string $dtoType,
    ) {
    }

    public function getDtoType(): string
    {
        return $this->dtoType;
    }
}
