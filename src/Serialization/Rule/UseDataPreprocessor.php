<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Rule;

use Attribute;
use Exterrestris\DtoFramework\Serialization\DataPreprocessor\DataPreprocessorInterface;

/**
 * @template DataPreprocessor of DataPreprocessorInterface
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class UseDataPreprocessor implements DataParserRule
{
    /**
     * @param class-string<DataPreprocessor> $dataPreprocessor
     */
    public function __construct(
        private string $dataPreprocessor,
    ) {
    }

    /**
     * @return DataPreprocessor
     */
    public function getDataPreprocessor(): DataPreprocessorInterface
    {
        return new $this->dataPreprocessor();
    }
}
