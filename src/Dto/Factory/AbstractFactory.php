<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory;

use Exterrestris\DtoFramework\Dto\Attributes\BaseDto;
use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\InternalPropertyException;
use Exterrestris\DtoFramework\Dto\Exceptions\NoSuchPropertyException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\FactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\UnknownTypeException;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

abstract class AbstractFactory implements FactoryInterface
{
    use GetAttributeTrait;

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return ReflectionClass
     * @throws FactoryException
     */
    protected function validateType(string $dtoType): ReflectionClass
    {
        try {
            $reflection = new ReflectionClass($dtoType);
        } catch (ReflectionException $e) {
            throw new UnknownTypeException($e->getMessage(), previous: $e);
        }

        if (!$this->isAcceptableType($reflection)) {
            throw new InvalidTypeException();
        }

        return $reflection;
    }

    protected function isAcceptableType(ReflectionClass $reflection): bool
    {
        return $reflection->implementsInterface(DtoInterface::class)
            && !$this->getAttribute($reflection, BaseDto::class);
    }

    /**
     * @param DtoInterface $dto
     * @param array<string, mixed>|object|null $data
     * @return DtoInterface
     */
    protected function populateDto(DtoInterface $dto, array|object|null $data = null): DtoInterface
    {
        if (!$data) {
            return $dto;
        }

        $reflection = new ReflectionObject($dto);
        $properties = array_combine(
            array_map(static fn (ReflectionProperty $property): mixed => $property->getName(), $reflection->getProperties()),
            $reflection->getProperties()
        );

        foreach ($data as $property => $value) {
            if (!isset($properties[$property])) {
                throw new NoSuchPropertyException($dto, $property);
            }

            if ($this->getAttribute($properties[$property], Internal::class)) {
                throw new InternalPropertyException($dto, $property);
            }

            $properties[$property]->setValue($dto, $value);
        }

        return $dto;
    }
}
