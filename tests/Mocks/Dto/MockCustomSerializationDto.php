<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Serializer\Config\OverrideDataExtractor;
use Exterrestris\DtoFramework\Serializer\Config\UseDataParserPreprocessor;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto\DataExtractor;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockCustomSerializationDto\DataParserPreprocessor as CustomPreprocessor;

#[OverrideDataExtractor(DataExtractor::class)]
#[UseDataParserPreprocessor(CustomPreprocessor::class)]
class MockCustomSerializationDto extends AbstractDto {
    protected string $name;
    protected string $title;
    protected ?bool $isProcessed = null;
    protected ?array $processingErrors = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): static
    {
        return $this->with(['name' => $name]);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): static
    {
        return $this->with(['title' => $title]);
    }

    public function isProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): static
    {
        return $this->with(['isProcessed' => $isProcessed]);
    }

    public function getProcessingErrors(): ?array
    {
        return $this->processingErrors;
    }

    public function setProcessingErrors(?array $processingErrors): static
    {
        return $this->with(['processingErrors' => $processingErrors]);
    }
}
