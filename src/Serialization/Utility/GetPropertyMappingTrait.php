<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Utility;

use Exterrestris\DtoFramework\Serialization\Rule\Map;
use Exterrestris\DtoFramework\Serialization\Rule\MapFrom;
use Exterrestris\DtoFramework\Serialization\Rule\MapTo;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
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
