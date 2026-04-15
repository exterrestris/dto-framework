<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto;

use BackedEnum;
use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\InternalPropertyException;
use Exterrestris\DtoFramework\Dto\Exceptions\InvalidDataException;
use Exterrestris\DtoFramework\Dto\Exceptions\NoSuchPropertyException;
use Exterrestris\DtoFramework\Dto\Metadata\BaseDto;
use Exterrestris\DtoFramework\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Utilities\GetAttributeTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

#[BaseDto]
abstract class AbstractDto implements DtoInterface
{
    use GetAttributeTrait;

    /**
     * Cache for {@see self::_getPropertiesToClone()}
     *
     * @var array<class-string<DtoInterface>, array<string, ReflectionProperty>>
     */
    private static array $cloneCache = [];

    /**
     * @param array<string, mixed> $dtoData
     */
    public function __construct(array $dtoData = []) {
        if ($dtoData) {
            if (array_is_list($dtoData)) {
                throw new InvalidDataException();
            }

            $this->_populateWithData($dtoData);
        }
    }

    /**
     * Helper method to assist in making setters immutable
     *
     * @param array<string, mixed>|string $newData
     * @param mixed $newValue
     * @return static
     */
    protected function with(array|string $newData, mixed $newValue = null): static
    {
        if (!$newData) {
            return $this;
        }

        if (is_string($newData)) {
            $newData = [$newData => $newValue];
        } elseif (array_is_list($newData)) {
            throw new InvalidDataException();
        }

        return (clone $this)->_populateWithData($newData);
    }

    /**
     * @param array<string, mixed> $data
     * @return static
     */
    private function _populateWithData(array $data): static
    {
        $reflection = new ReflectionClass($this);

        foreach ($data as $property => $value) {
            try {
                $reflectionProperty = $reflection->getProperty($property);
            } catch (ReflectionException $e) {
                throw new NoSuchPropertyException($this, $property, previous: $e);
            }

            if ($this->getAttribute($reflectionProperty, Internal::class)) {
                throw new InternalPropertyException($this, $property);
            }

            $reflectionProperty->setValue($this, $value);
        }

        return $this;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        foreach ($this->_getPropertiesToClone() as $reflectionProperty) {
            if (!$reflectionProperty->isInitialized($this)) {
                continue;
            }

            $value = $reflectionProperty->getValue($this);

            if ($value === null) {
                continue;
            }

            $reflectionProperty->setValue($this, clone $value);
        }
    }

    /**
     * Get a list of properties with types that will need to be cloned when cloning the DTO
     *
     * Fetches a list of properties that must be cloned when cloning the DTO, i.e. properties with object types such as
     * {@see DateTimeInterface}, {@see DtoInterface}, {@see CollectionInterface} etc.
     *
     * @return array<string, ReflectionProperty>
     * @see self::$cloneCache
     */
    private function _getPropertiesToClone(): array
    {
        if (!isset(self::$cloneCache[static::class])) {
            self::$cloneCache[static::class] = [];

            $reflect = new ReflectionClass($this);

            foreach ($reflect->getProperties() as $reflectionProperty) {
                $reflectionType = $reflectionProperty->getType();

                if (
                    $reflectionType->isBuiltin() ||
                    is_a($reflectionType->getName(), BackedEnum::class, true) ||
                    $this->getAttribute($reflectionProperty, Internal::class)
                ) {
                    continue;
                }

                self::$cloneCache[static::class][$reflectionProperty->getName()] = $reflectionProperty;
            }
        }

        return self::$cloneCache[static::class];
    }
}
