<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utility;

use ReflectionClass;
use ReflectionProperty;

trait GetAttributeTrait
{
    /**
     * @template Attr of object
     * @param ReflectionProperty|ReflectionClass $reflect
     * @param class-string<Attr> $attribute
     * @return ?Attr
     */
    protected function getAttribute(ReflectionProperty|ReflectionClass $reflect, string $attribute): ?object
    {
        if ($reflect->getAttributes($attribute)) {
            return $reflect->getAttributes($attribute)[0]->newInstance();
        }

        return null;
    }
}
