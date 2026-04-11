<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Traits;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidCollectionDtoException;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidCollectionException;
use Exterrestris\DtoFramework\Validator\Exceptions\InvalidDtoException;

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
                $failed[] = new InvalidCollectionDtoException($collection, $index, $e);
            }
        }

        if ($failed) {
            throw new InvalidCollectionException($collection, $failed);
        }

        return $collection;
    }
}
