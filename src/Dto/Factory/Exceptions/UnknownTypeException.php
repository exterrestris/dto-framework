<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Factory\Exceptions;

use InvalidArgumentException;

class UnknownTypeException extends InvalidArgumentException implements FactoryException
{

}
