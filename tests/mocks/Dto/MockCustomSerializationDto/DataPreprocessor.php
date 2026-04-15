<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;

use Exterrestris\DtoFramework\Serialization\DataPreprocessor\DataPreprocessorInterface;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\Exception\UnsupportedDtoTypeException;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto;

class DataPreprocessor implements DataPreprocessorInterface
{
    public function preprocess(mixed $data, string $dtoType): ?array
    {
        if (!is_a($dtoType, MockCustomSerializationDto::class, true)) {
            throw new UnsupportedDtoTypeException();
        }

        if ($data === null) {
            return null;
        }

        reset($data);

        $name = key($data);
        $title = current($data);

        if (is_array($title)) {
            return array_map(function($data) use ($dtoType) {
                return $this->preprocess($data, $dtoType);
            }, $data);
        }

        return [
            'name' => $name,
            'title' => $title,
        ];
    }
}
