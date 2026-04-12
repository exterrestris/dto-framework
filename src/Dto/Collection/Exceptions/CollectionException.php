<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Exceptions\DtoFrameworkException;

interface CollectionException extends DtoFrameworkException
{
    public function getCollection(): ?CollectionInterface;
}
