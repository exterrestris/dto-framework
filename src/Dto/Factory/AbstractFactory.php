<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Exceptions\InternalPropertyException;
use Exterrestris\DtoFramework\Dto\Exceptions\NoSuchPropertyException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\InvalidTypeException as InvalidTypeFactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\UnknownTypeException as UnknownTypeFactoryException;
use Exterrestris\DtoFramework\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Exceptions\Internal\InvalidTypeException as InvalidTypeInternalException;
use Exterrestris\DtoFramework\Exceptions\Internal\UnknownTypeException as UnknownTypeInternalException;
use Exterrestris\DtoFramework\Traits\CheckAcceptableTypeTrait;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

abstract class AbstractFactory implements FactoryInterface
{
    use CheckAcceptableTypeTrait;

    protected function validateType(string $dtoType): ReflectionClass
    {
        try {
            return $this->verifyIsAcceptableType($dtoType);
        } catch (UnknownTypeInternalException $e) {
            throw UnknownTypeFactoryException::from($e);
        } catch (InvalidTypeInternalException $e) {
            throw InvalidTypeFactoryException::from($e);
        }
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
