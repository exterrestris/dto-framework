<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Traits;

use Exterrestris\DtoFramework\Serializer\Rules\Map;
use Exterrestris\DtoFramework\Serializer\Rules\MapFrom;
use Exterrestris\DtoFramework\Serializer\Rules\MapTo;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use ReflectionProperty;

trait GetPropertyMappingTrait
{
    use GetAttributeTrait;

    protected function mapNameTo(ReflectionProperty $property): string
    {
        return $this->getAttribute($property, MapTo::class)?->getMapping() ?? $this->mapName($property);
    }

    protected function mapName(ReflectionProperty $property): string
    {
        return $this->getAttribute($property, Map::class)?->getMapping() ?? $property->getName();
    }

    protected function mapNameFrom(ReflectionProperty $property): string
    {
        return $this->getAttribute($property, MapFrom::class)?->getMapping() ?? $this->mapName($property);
    }
}
