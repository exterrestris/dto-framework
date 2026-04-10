<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Traits\GetAttributeTrait;

readonly class Validator extends AbstractValidator implements ValidatorInterface
{
    use GetAttributeTrait;

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
