<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Traits;

use Exterrestris\DtoFramework\Dto\DtoInterface as Serializable;
use Exterrestris\DtoFramework\Serializer\Rules\DateFormat;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use ReflectionProperty;

trait GetPropertyDateFormatTrait
{
    use GetAttributeTrait;

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    protected function getDateFormat(ReflectionProperty $property): string
    {
        return $this->getAttribute($property, DateFormat::class)?->getFormat() ??
            $this->getAttribute($property->getDeclaringClass(), DateFormat::class)?->getFormat() ??
            Serializable::DEFAULT_DATE_FORMAT;
    }
}
