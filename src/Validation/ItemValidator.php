<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\Exceptions\ValidationException;

/**
 * @template Dto of DtoInterface
 * @template Collection of CollectionInterface<Dto>
 */
interface ItemValidator
{
    /**
     * @param Dto|Collection $item
     * @param bool $enforcePreferences
     * @return Dto|Collection
     * @throws ValidationException
     */
    public function validate(
        DtoInterface|CollectionInterface $item,
        bool $enforcePreferences = false
    ): DtoInterface|CollectionInterface;
}
