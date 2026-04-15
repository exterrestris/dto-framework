<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serialization\Rule;

use Attribute;
use Exterrestris\DtoFramework\Serialization\DataExtractor\DataExtractorInterface;

/**
 * @template Extractor of DataExtractorInterface
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class UseDataExtractor implements DataExtractorRule
{
    /**
     * @param class-string<Extractor> $dataExtractor
     */
    public function __construct(
        private string $dataExtractor,
    ) {
    }

    /**
     * @return Extractor
     */
    public function getDataExtractor(): DataExtractorInterface
    {
        return new $this->dataExtractor;
    }
}
