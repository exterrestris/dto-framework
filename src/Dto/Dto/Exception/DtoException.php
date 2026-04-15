<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Exception;

use Exterrestris\DtoFramework\Dto\Dto\DtoInterface;
use Exterrestris\DtoFramework\Exception\DtoFrameworkException;

interface DtoException extends DtoFrameworkException
{
    public function getDto(): ?DtoInterface;
}
