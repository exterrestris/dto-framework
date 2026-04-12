<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validators;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validation\ItemValidator;
use Exterrestris\DtoFramework\Validation\Traits\ValidateCollectionTrait;
use Exterrestris\DtoFramework\Validation\Traits\ValidateDtoTrait;

readonly class Validator implements ItemValidator
{
    use ValidateCollectionTrait;
    use ValidateDtoTrait;

    /**
     * @inheritDoc
     */
    public function validate(
        DtoInterface|CollectionInterface $item,
        bool $enforcePreferences = false
    ): DtoInterface|CollectionInterface {
        if ($item instanceof CollectionInterface) {
            return $this->validateCollection($item, $enforcePreferences);
        }

        return $this->validateDto($item, $enforcePreferences);
    }
}
