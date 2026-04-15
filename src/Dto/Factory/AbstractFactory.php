<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Dto\Factory\Exception\InternalDtoPropertyException as InternalPropertyFactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exception\InvalidTypeException as InvalidTypeFactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exception\NoSuchDtoPropertyException as NoSuchPropertyFactoryException;
use Exterrestris\DtoFramework\Dto\Factory\Exception\UnknownTypeException as UnknownTypeFactoryException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\InternalDtoPropertyException as InternalPropertyUtilityException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\Exception\NoSuchDtoPropertyException as NoSuchPropertyUtilityException;
use Exterrestris\DtoFramework\Dto\Dto\Utility\PopulateDtoTrait;
use Exterrestris\DtoFramework\Exception\Internal\InvalidTypeException as InvalidTypeInternalException;
use Exterrestris\DtoFramework\Exception\Internal\UnknownTypeException as UnknownTypeInternalException;
use Exterrestris\DtoFramework\Utility\CheckAcceptableTypeTrait;
use ReflectionClass;

abstract class AbstractFactory implements FactoryInterface
{
    use CheckAcceptableTypeTrait;
    use PopulateDtoTrait;

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

    protected function populateDto(DtoInterface $dto, array|object|null $withData = null): DtoInterface
    {
        try {
            return $this->_populateDto($dto, $withData);
        } catch (InternalPropertyUtilityException $e) {
            throw InternalPropertyFactoryException::from($e);
        } catch (NoSuchPropertyUtilityException $e) {
            throw NoSuchPropertyFactoryException::from($e);
        }
    }
}
