<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Collection\Exceptions;

use OutOfBoundsException;

class InvalidIndexException extends OutOfBoundsException implements CollectionException
{

}
