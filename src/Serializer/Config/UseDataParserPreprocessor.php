<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Config;

use Attribute;
use Exterrestris\DtoFramework\Serializer\DataParserPreprocessorInterface;

/**
 * @template DataPreprocessor of DataParserPreprocessorInterface
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class UseDataParserPreprocessor
{
    /**
     * @param class-string<UseDataParserPreprocessor> $dataPreprocessor
     */
    public function __construct(
        private string $dataPreprocessor,
    ) {
    }

    /**
     * @return DataParserPreprocessorInterface
     */
    public function getDataPreprocessor(): DataParserPreprocessorInterface
    {
        return new $this->dataPreprocessor();
    }
}
