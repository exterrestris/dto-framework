<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use DateTimeImmutable;
use Exterrestris\DtoFramework\Dto\AbstractProcessableDto;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Metadata\CollectionType;
use Exterrestris\DtoFramework\Dto\Metadata\DateFormat;
use Exterrestris\DtoFramework\Dto\Metadata\Internal;
use Exterrestris\DtoFramework\Serializer\Rules\Map;
use Exterrestris\DtoFramework\Serializer\Rules\MapFrom;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerialize;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerializeIfNull;
use Exterrestris\DtoFramework\Tests\Mocks\Validator\Rules\Title;
use Exterrestris\DtoFramework\Utilities\DateRoundingMode;
use Exterrestris\DtoFramework\Validation\Rules\DateAfter;
use Exterrestris\DtoFramework\Validation\Rules\MatchRegex;
use Exterrestris\DtoFramework\Validation\Rules\StringMaxLengthPreference;

class MockDto extends AbstractProcessableDto implements MockDtoInterface
{
    #[StringMaxLengthPreference(15, 25)]
    #[MatchRegex('/^[a-z ]+$/')]
    #[Map('fullName')]
    protected string $name;
    #[NoSerializeIfNull]
    #[Title]
    protected ?string $title = null;
    #[Internal]
    protected bool $internal;
    protected string $uninitialized;
    #[DateAfter('01/01/2025')]
    #[DateFormat('d/m/Y', DateRoundingMode::ToStart)]
    protected ?DateTimeImmutable $date = null;
    #[NoSerialize]
    #[DateFormat('d/m/Y', DateRoundingMode::ToEnd)]
    protected ?DateTimeImmutable $endDate = null;
    #[NoSerialize]
    #[DateFormat('d/m/Y', DateRoundingMode::None)]
    protected ?DateTimeImmutable $otherDate = null;
    #[NoSerialize]
    #[MapFrom('processed')]
    protected ?bool $isProcessed = null;
    #[NoSerialize]
    protected ?array $processingErrors = null;
    #[NoSerializeIfNull]
    #[CollectionType(MockDto::class)]
    protected ?CollectionInterface $children = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        return $this->with('name', $name);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        return $this->with('title', $title);
    }

    public function isInternal(): bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): static
    {
        $this->internal = $internal;
        return $this;
    }

    public function getUninitialized(): string
    {
        return $this->uninitialized;
    }

    public function setUninitialized(string $uninitialized): static
    {
        return $this->with('uninitialized', $uninitialized);
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): static
    {
        return $this->with('date', $date);
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeImmutable $date): static
    {
        return $this->with('endDate', $date);
    }

    public function getOtherDate(): ?DateTimeImmutable
    {
        return $this->otherDate;
    }

    public function setOtherDate(?DateTimeImmutable $date): static
    {
        return $this->with('otherDate', $date);
    }

    public function isProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): static
    {
        return $this->with('isProcessed', $isProcessed);
    }

    public function getProcessingErrors(): ?array
    {
        return $this->processingErrors;
    }

    public function setProcessingErrors(?array $processingErrors): static
    {
        return $this->with('processingErrors', $processingErrors);
    }

    public function getChildren(): ?CollectionInterface
    {
        return $this->children;
    }

    public function setChildren(?CollectionInterface $children): static
    {
        return $this->with('children', $children);
    }
}
