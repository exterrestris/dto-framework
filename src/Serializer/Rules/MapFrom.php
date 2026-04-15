<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Rules;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class MapFrom
{
    public function __construct(
        private string $mapping,
    ) {
    }

    public function getMapping(): string
    {
        return $this->mapping;
    }
}
