<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\DataParser\Exception;

use Exterrestris\DtoFramework\Serialization\Exception\UnsupportedDataTypeException;

class UnparsableDataTypeException extends AbstractDataParserException implements UnsupportedDataTypeException
{
}
