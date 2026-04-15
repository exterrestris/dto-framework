<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Exceptions;

use DomainException;
use Throwable;

abstract class AbstractDataParserException extends DomainException implements DataParserException
{
    /**
     * @param string $message
     * @param array|object|null $data
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        protected readonly array|object|null $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getData(): array|object|null
    {
        return $this->data;
    }

    public static function withData(DataParserException $exception, object|array|null $data = null): static
    {
        return new static($exception->getMessage(), $data, $exception->getPrevious());
    }
}
