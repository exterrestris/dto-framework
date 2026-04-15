<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Traits;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidCollectionDtoException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidCollectionException;
use Exterrestris\DtoFramework\Validation\Exceptions\InvalidDtoException;

/**
 * @template Dto of DtoInterface
 * @template Collection of CollectionInterface<Dto>
 */
trait ValidateCollectionTrait
{
    use ValidateDtoTrait;

    /**
     * @param Collection $collection
     * @param bool $enforcePreferences
     * @return Collection
     * @throws InvalidCollectionException
     */
    protected function validateCollection(
        CollectionInterface $collection,
        bool $enforcePreferences = false
    ): CollectionInterface {
        $failed = [];

        foreach ($collection as $index => $dto) {
            try {
                $this->validateDto($dto, $enforcePreferences);
            } catch (InvalidDtoException $e) {
                $failed[] = InvalidCollectionDtoException::from($e, $collection, $index);
            }
        }

        if ($failed) {
            throw new InvalidCollectionException($collection, $failed);
        }

        return $collection;
    }
}
