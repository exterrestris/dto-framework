<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto;

use BackedEnum;
use DateTimeInterface;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\Exception\InternalDtoPropertyException as InternalPropertyDtoException;
use Exterrestris\DtoFramework\Dto\Dto\Exception\InvalidDataException;
use Exterrestris\DtoFramework\Dto\Dto\Exception\NoSuchDtoPropertyException as NoSuchPropertyDtoException;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\BaseDto;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\InternalDtoPropertyException as InternalPropertyUtilityException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\NoSuchDtoPropertyException as NoSuchPropertyUtilityException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\PopulateDtoTrait;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
use ReflectionClass;
use ReflectionProperty;

#[BaseDto]
abstract class AbstractDto implements DtoInterface
{
    use GetAttributeTrait;
    use PopulateDtoTrait;

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
        }

        return (clone $this)->_populateWithData($newData);
    }

    /**
     * @param array<string, mixed> $data
     * @return static
     */
    private function _populateWithData(array $data): static
    {
        if (array_is_list($data)) {
            throw new InvalidDataException();
        }

        try {
            return $this->_populateDto($this, $data);
        } catch (InternalPropertyUtilityException $e) {
            throw InternalPropertyDtoException::from($e);
        } catch (NoSuchPropertyUtilityException $e) {
            throw NoSuchPropertyDtoException::from($e);
        }
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
