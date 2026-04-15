<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Collection\Exceptions\CollectionException;

interface CollectionValidationException extends CollectionException, ValidationException
{
    public function getInvalidCollection(): CollectionInterface;
}
