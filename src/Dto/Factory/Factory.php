<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory;

use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\UnknownTypeException;
use Exterrestris\DtoFramework\Dto\Factory\Exceptions\UnsupportedTypeException;
use ReflectionObject;

class Factory extends AbstractFactory
{

    /**
     * @param class-string<DtoInterface> $interface
     * @return class-string<DtoInterface>[]
     */
    protected function getInterfaceImplementationMapping(string $interface): array
    {
        return [
            preg_replace('/Interface$/', '', $interface)
        ];
    }

    /**
     * @inheritDoc
     */
    public function create(string $dtoType, array|object|null $withData = null): DtoInterface
    {
        $reflection = $this->validateType($dtoType);

        if ($reflection->isInterface()) {
            foreach ($this->getInterfaceImplementationMapping($dtoType) as $implementation) {
                try {
                    return $this->create($implementation, $withData);
                } catch (UnknownTypeException) {
                }
            }

            throw new UnsupportedTypeException('Cannot create DTO from interface');
        }

        return $this->populateDto(new $dtoType, $withData);
    }

    /**
     * @inheritDoc
     */
    public function createCollection(string $ofDtoType): CollectionInterface
    {
        $this->validateType($ofDtoType);

        return new Collection($ofDtoType);
    }

    /**
     * @inheritDoc
     */
    public function createCollectionFor(DtoInterface ...$items): CollectionInterface
    {
        $reflect = new ReflectionObject($items[0]);
        $dtoType = $items[0]::class;

        foreach ($reflect->getInterfaces() as $interface) {
            if ($this->isAcceptableType($interface)) {
                $dtoType = $interface->getName();
                break;
            }
        }

        return $this->createCollection($dtoType)->add(...$items);
    }
}
