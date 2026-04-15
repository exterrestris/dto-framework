<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataExtractor;

use BackedEnum;
use DateTimeInterface;
use Error;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\DataExtractorException;
use Exterrestris\DtoFramework\Serialization\DataExtractor\Exception\ValueSerializationException;
use Exterrestris\DtoFramework\Serialization\Rule\NoSerialize;
use Exterrestris\DtoFramework\Serialization\Rule\NoSerializeIfNull;
use Exterrestris\DtoFramework\Serialization\Rule\UseDataExtractor;
use Exterrestris\DtoFramework\Serialization\Utility\GetPropertyMappingTrait;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
use Exterrestris\DtoFramework\Utility\GetPropertyDateFormatTrait;
use ReflectionObject;
use ReflectionProperty;
use stdClass;

class DataExtractor implements DataExtractorInterface
{
    use GetAttributeTrait;
    use GetPropertyMappingTrait;
    use GetPropertyDateFormatTrait;

    public function getData(
        DtoInterface|CollectionInterface|null $serializable,
        bool $excludeNoSerialize = true
    ): array|string|null {
        if ($serializable === null) {
            return null;
        }

        if ($serializable instanceof CollectionInterface) {
            return array_map(function ($dto) use ($excludeNoSerialize) {
                return $this->getData($dto, $excludeNoSerialize);
            }, $serializable->toArray());
        }

        $reflect = new ReflectionObject($serializable);
        $overrideExtractor = $this->getAttribute($reflect, UseDataExtractor::class);

        return $overrideExtractor?->getDataExtractor()->getData($serializable, $excludeNoSerialize)
            ?? $this->extractData($serializable, $reflect, $excludeNoSerialize);
    }

    /**
     * @throws DataExtractorException
     */
    protected function extractData(
        DtoInterface $dto,
        ReflectionObject $reflect,
        bool $excludeNoSerialize = true
    ): array {
        $data = [];

        foreach ($reflect->getProperties() as $property) {
            if ($this->isInternal($property) || ($this->noSerialise($property, $dto) && $excludeNoSerialize)) {
                continue;
            }

            if (!$property->isInitialized($dto)) {
                $data[$this->mapNameTo($property)] = null;

                if ($excludeNoSerialize && $property->getType()->isBuiltin() && !$property->getType()->allowsNull()) {
                    try {
                        if ($property->getType()->getName() !== 'mixed') {
                            settype($data[$this->mapNameTo($property)], $property->getType()->getName());
                        }
                    } catch (Error) {
                    }
                }

                continue;
            }

            $data[$this->mapNameTo($property)] = $this->getValue($property, $dto, $excludeNoSerialize);
        }

        return $data;
    }

    protected function isInternal(ReflectionProperty $property): bool
    {
        return (bool) $this->getAttribute($property, Internal::class);
    }

    protected function noSerialise(ReflectionProperty $property, DtoInterface $dto): bool
    {
        return $this->getAttribute($property, NoSerialize::class) || (
            $this->getAttribute($property, NoSerializeIfNull::class) && (
                !$property->isInitialized($dto) || $property->getValue($dto) === null
            ));
    }

    /**
     * @param ReflectionProperty $property
     * @param DtoInterface $dto
     * @param bool $excludeNoSerialize
     * @return array|string|float|int|bool|null
     * @throws DataExtractorException
     */
    protected function getValue(
        ReflectionProperty $property,
        DtoInterface $dto,
        bool $excludeNoSerialize = true,
    ): array|string|float|int|bool|null {
        $propertyValue = $property->getValue($dto);

        if (is_scalar($propertyValue) || is_null($propertyValue) || is_array($propertyValue)) {
            return $propertyValue;
        }

        if ($propertyValue instanceof stdClass) {
            return (array) $propertyValue;
        }

        if ($propertyValue instanceof DtoInterface || $propertyValue instanceof CollectionInterface) {
            return $this->getData($propertyValue, $excludeNoSerialize);
        }

        if ($propertyValue instanceof DateTimeInterface) {
            return $propertyValue->format($this->getDateFormatString($property));
        }

        if ($propertyValue instanceof BackedEnum) {
            return $propertyValue->value;
        }

        throw new ValueSerializationException(sprintf('Cannot serialize property "%s"', $property->getName()));
    }
}
