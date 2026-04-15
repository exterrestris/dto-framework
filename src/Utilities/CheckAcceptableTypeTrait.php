<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utilities;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Metadata\BaseDto;
use Exterrestris\DtoFramework\Exceptions\Internal\InvalidTypeException;
use Exterrestris\DtoFramework\Exceptions\Internal\TypeException;
use Exterrestris\DtoFramework\Exceptions\Internal\UnknownTypeException;
use ReflectionClass;
use ReflectionException;

trait CheckAcceptableTypeTrait
{
    use GetAttributeTrait;

    protected function isAcceptableType(ReflectionClass $reflection): bool
    {
        return $reflection->implementsInterface(DtoInterface::class)
            && !$this->getAttribute($reflection, BaseDto::class);
    }

    /**
     * @param class-string<DtoInterface> $dtoType
     * @return ReflectionClass
     * @throws TypeException These must be caught and rethrown as exceptions applicable for where this method is called
     */
    protected function verifyIsAcceptableType(string $dtoType): ReflectionClass
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
}
