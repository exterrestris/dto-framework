<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exception;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exception\CollectionException;

interface CollectionValidationException extends CollectionException, ValidationException
{
    public function getInvalidCollection(): CollectionInterface;
}
