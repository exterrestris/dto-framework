<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer;

use BackedEnum;
use DateTimeInterface;
use Error;
use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataExtractorException;
use Exterrestris\DtoFramework\Serializer\Exceptions\ValueSerializationException;
use Exterrestris\DtoFramework\Serializer\Traits\GetPropertyDateFormatTrait;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use ReflectionObject;
use ReflectionProperty;
use stdClass;

class DataExtractor implements DataExtractorInterface
{
    use GetAttributeTrait;
    use GetPropertyDateFormatTrait;

    public function getData(DtoInterface|CollectionInterface|null $serializable): array|string|null {
        if ($serializable === null) {
            return null;
        }

        if ($serializable instanceof CollectionInterface) {
            return array_map(function ($dto) {
                return $this->getData($dto);
            }, $serializable->toArray());
        }

        $reflect = new ReflectionObject($serializable);

        return $this->extractData($serializable, $reflect);
    }

    /**
     * @throws DataExtractorException
     */
    protected function extractData(DtoInterface $dto, ReflectionObject $reflect): array {
        $data = [];

        foreach ($reflect->getProperties() as $property) {
            if ($this->isInternal($property)) {
                continue;
            }

            if (!$property->isInitialized($dto)) {
                $data[$property->getName()] = null;

                if ($property->getType()->isBuiltin() && !$property->getType()->allowsNull()) {
                    try {
                        if ($property->getType()->getName() !== 'mixed') {
                            settype($data[$property->getName()], $property->getType()->getName());
                        }
                    } catch (Error) {
                    }
                }

                continue;
            }

            $data[$property->getName()] = $this->getValue($property, $dto);
        }

        return $data;
    }

    protected function isInternal(ReflectionProperty $property): bool
    {
        return (bool) $this->getAttribute($property, Internal::class);
    }

    /**
     * @param ReflectionProperty $property
     * @param DtoInterface $dto
     * @return array|string|float|int|bool|null
     * @throws DataExtractorException
     */
    protected function getValue(
        ReflectionProperty $property,
        DtoInterface $dto,
    ): array|string|float|int|bool|null {
        $propertyValue = $property->getValue($dto);

        if (is_scalar($propertyValue) || is_null($propertyValue) || is_array($propertyValue)) {
            return $propertyValue;
        }

        if ($propertyValue instanceof stdClass) {
            return (array) $propertyValue;
        }

        if ($propertyValue instanceof DtoInterface || $propertyValue instanceof CollectionInterface) {
            return $this->getData($propertyValue);
        }

        if ($propertyValue instanceof DateTimeInterface) {
            return $propertyValue->format($this->getDateFormat($property));
        }

        if ($propertyValue instanceof BackedEnum) {
            return $propertyValue->value;
        }

        throw new ValueSerializationException(sprintf('Cannot serialize property "%s"', $property->getName()));
    }
}
