<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Rules;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS)]
final readonly class DateFormat
{
    public function __construct(
        private string $format,
    ) {
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
