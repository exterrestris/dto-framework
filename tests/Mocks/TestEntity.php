<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks;

use Exterrestris\DtoFramework\Dto\AbstractProcessableDto;
use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Attributes\Internal;
use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Serializer\Rules\Map;
use Exterrestris\DtoFramework\Serializer\Rules\MapFrom;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerialize;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerializeIfNull;
use Exterrestris\DtoFramework\Tests\Mocks\Validator\Rules\Title;
use Exterrestris\DtoFramework\Validator\Rules\MatchRegex;
use Exterrestris\DtoFramework\Validator\Rules\StringMaxLengthPreference;

class TestEntity extends AbstractProcessableDto implements TestEntityInterface
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
    #[NoSerialize]
    #[MapFrom('processed')]
    protected ?bool $isProcessed = null;
    #[NoSerialize]
    protected ?array $processingErrors = null;
    #[NoSerializeIfNull]
    #[CollectionType(TestEntity::class)]
    protected ?CollectionInterface $children = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TestEntity
    {
        $new = clone $this;
        $new->name = $name;
        return $new;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): TestEntity
    {
        $new = clone $this;
        $new->title = $title;
        return $new;
    }

    public function isInternal(): bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): TestEntity
    {
        $new = clone $this;
        $new->internal = $internal;
        return $new;
    }

    public function getUninitialized(): string
    {
        return $this->uninitialized;
    }

    public function setUninitialized(string $uninitialized): TestEntity
    {
        $new = clone $this;
        $new->uninitialized = $uninitialized;
        return $new;
    }

    public function isProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): static
    {
        $new = clone $this;
        $new->isProcessed = $isProcessed;
        return $new;
    }

    public function getProcessingErrors(): ?array
    {
        return $this->processingErrors;
    }

    public function setProcessingErrors(?array $processingErrors): static
    {
        $new = clone $this;
        $new->processingErrors = $processingErrors;
        return $new;
    }

    public function getChildren(): ?CollectionInterface
    {
        return $this->children;
    }

    public function setChildren(?CollectionInterface $children): static
    {
        $new = clone $this;
        $new->children = $children;
        return $new;
    }
}
