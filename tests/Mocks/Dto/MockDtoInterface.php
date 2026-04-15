<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;
use Exterrestris\DtoFramework\Tests\Mocks\Dto\MockDto;

interface MockDtoInterface extends ProcessableDtoInterface
{
    public function getName(): string;

    public function setName(string $name): MockDto;

    public function getTitle(): ?string;

    public function setTitle(?string $title): MockDto;

    public function isInternal(): bool;

    public function setInternal(bool $internal): MockDto;

    public function getUninitialized(): string;

    public function setUninitialized(string $uninitialized): MockDto;

    public function getChildren(): ?CollectionInterface;

    public function setChildren(?CollectionInterface $children): static;
}
