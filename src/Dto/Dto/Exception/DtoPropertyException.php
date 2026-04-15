<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Exception;

interface DtoPropertyException extends DtoException
{
    public function getProperty(): string;
}
