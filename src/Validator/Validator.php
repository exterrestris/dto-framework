<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Validator\Traits\ValidateCollectionTrait;
use Exterrestris\DtoFramework\Validator\Traits\ValidateDtoTrait;

readonly class Validator implements ValidatorInterface
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
