<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Utility;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\InternalDtoPropertyException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\NoSuchDtoPropertyException;
use Exterrestris\DtoFramework\Utility\GetAttributeTrait;
use ReflectionException;
use ReflectionObject;

/**
 * @template Dto of DtoInterface
 */
trait PopulateDtoTrait
{
    use GetAttributeTrait;

    /**
     * @param Dto $dto
     * @param array<string, mixed>|object|null $data
     * @return Dto
     */
    protected function _populateDto(DtoInterface $dto, array|object|null $data = null): DtoInterface
    {
        if (!$data) {
            return $dto;
        }

        $reflection = new ReflectionObject($dto);

        foreach ($data as $property => $value) {
            try {
                $reflectionProperty = $reflection->getProperty($property);
            } catch (ReflectionException $e) {
                throw new NoSuchDtoPropertyException($dto, $property, previous: $e);
            }

            if ($this->getAttribute($reflectionProperty, Internal::class)) {
                throw new InternalDtoPropertyException($dto, $property);
            }

            $reflectionProperty->setValue($dto, $value);
        }

        return $dto;
    }
}
