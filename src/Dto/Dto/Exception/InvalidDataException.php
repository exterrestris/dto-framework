<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto\Exception;

class InvalidDataException extends \InvalidArgumentException implements DtoException
{
    public function getDto(): null
    {
        return null;
    }
}
