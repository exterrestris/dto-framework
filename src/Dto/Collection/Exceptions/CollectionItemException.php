<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;

interface CollectionItemException extends CollectionException
{
    public function getDto(): DtoInterface;
}
