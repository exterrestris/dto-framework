<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Exceptions;

use Exterrestris\DtoFramework\Dto\DtoInterface;
use Exterrestris\DtoFramework\Exceptions\DtoFrameworkException;

interface DtoException extends DtoFrameworkException
{
    public function getDto(): ?DtoInterface;
}
