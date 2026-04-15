<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Validator;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;

readonly class ItemValidator implements ItemValidatorInterface
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
