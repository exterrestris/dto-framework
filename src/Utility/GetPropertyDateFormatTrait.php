<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use Exterrestris\DtoFramework\Dto\Dto\Metadata\DateFormat;
use ReflectionProperty;

trait GetPropertyDateFormatTrait
{
    use GetAttributeTrait;

    protected function getDateFormat(ReflectionProperty $property): ?DateFormat
    {
        return $this->getAttribute($property, DateFormat::class)
            ?? $this->getAttribute($property->getDeclaringClass(), DateFormat::class);
    }

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    protected function getDateFormatString(ReflectionProperty $property): string
    {
        return $this->getDateFormat($property)?->getFormat() ?? DateFormat::DEFAULT_DATE_FORMAT;
    }
}
