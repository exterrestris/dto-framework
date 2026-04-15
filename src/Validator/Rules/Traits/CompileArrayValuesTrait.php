<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules\Traits;

trait CompileArrayValuesTrait
{
    protected function compileValuesUsingFormat(
        array $values,
        string $singleValueFormat,
        string $multiValueFormat,
    ): string {
        // Ensure 0-indexed array
        $values = array_values($values);

        if (count($values) === 1) {
            return sprintf($singleValueFormat, $values[0]);
        }

        sort($values);

        return sprintf(
            $multiValueFormat,
            implode('", "', array_slice($values, 0, -1)),
            $values[array_key_last($values)]
        );
    }

    protected function compileValues(array $values, string $joinPhrase = 'or'): string {
        return $this->compileValuesUsingFormat($values, '"%s"', '"%s" ' . $joinPhrase . ' "%s"');
    }
}
