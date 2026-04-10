<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Serializer\Config;

use Attribute;
use Exterrestris\DtoFramework\Serializer\DataExtractorInterface;

/**
 * @template Extractor of DataExtractorInterface
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class OverrideDataExtractor
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
