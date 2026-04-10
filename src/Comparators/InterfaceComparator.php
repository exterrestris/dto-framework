<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Comparators;

use Exterrestris\DtoFramework\Comparators\Exceptions\InvalidTypeException;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\DataExtractorException;
use ReflectionClass;
use ReflectionException;

/**
 * Compare DTOs using *some* of their properties, as described by the specified interface
 *
 * Intended for use with APIs that return a subset of a DTO that must be matched against the full DTO
 *
 * @template Dto of DtoInterface
 */
final class InterfaceComparator extends AbstractComparator
{
    private ReflectionClass $reflect;

    /**
     * @param class-string<Dto> $dtoType
     * @param DataExtractorInterface $dataExtractor
     */
    public function __construct(
        private readonly string $dtoType,
        private readonly DataExtractorInterface $dataExtractor
    )
    {
        try {
            $this->reflect = new ReflectionClass($this->dtoType);
        } catch (ReflectionException $e) {
            throw new InvalidTypeException('Cannot reflect supplied type', previous: $e);
        }

        if (!$this->reflect->isInterface()) {
            throw new InvalidTypeException(sprintf('"%s" must be an interface', $this->reflect->getName()));
        }

        if (!$this->reflect->implementsInterface(DtoInterface::class)) {
            throw new InvalidTypeException(sprintf('"%s" must extend "%s"', $this->reflect->getName(), DtoInterface::class));
        }
    }

    /**
     * @throws ReflectionException
     * @throws DataExtractorException
     */
    private function getData(DtoInterface $dto): array
    {
        $data = [];

        foreach ($this->reflect->getMethods() as $method) {
            if (str_starts_with($method->getName(), 'get') || str_starts_with($method->getName(), 'is')) {
                $value = $dto->{$method->getName()}();

                if ($value instanceof DtoInterface || $value instanceof CollectionInterface) {
                    $value = $this->dataExtractor->getData($value);
                }

                $data[$method->getName()] = $value;
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function compare(DtoInterface $a, DtoInterface $b): int
    {
        try {
            if ($a instanceof $this->dtoType && $b instanceof $this->dtoType) {
                $a = $this->getData($a);
                $b = $this->getData($b);
                $cmp = $a <=> $b;

                if ($cmp === 0) {
                    return $a === $b ? 0 : -1;
                }

                return $cmp;
            } else {
                // $a and $b *cannot* be considered equal
                return $a instanceof $this->dtoType ? -1 : 1;
            }
        } catch (DataExtractorException|ReflectionException) {
            return -1;
        }
    }

    public function couldMatch(string $dtoType): bool
    {
        return is_a($dtoType, $this->dtoType, true);
    }
}
