<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Exceptions;

use DomainException;

class NoSuchPropertyException extends DomainException implements DtoException
{
}
