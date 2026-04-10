<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;

use Exterrestris\DtoFramework\Serializer\DataParserPreprocessorInterface;
use Exterrestris\DtoFramework\Serializer\Exceptions\UnsupportedDtoTypeException;
use Exterrestris\DtoFramework\Tests\Mocks\CustomSerializationEntity;

class DataParserPreprocessor implements DataParserPreprocessorInterface
{
    public function preprocess(mixed $data, string $dtoType): ?array
    {
        if (!is_a($dtoType, CustomSerializationEntity::class, true)) {
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
