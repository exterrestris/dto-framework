<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\Dto\ProcessableDtoInterface;

interface MockDtoInterface extends MockNamedDtoInterface, ProcessableDtoInterface
{
    public function isInternal(): bool;

    public function setInternal(bool $internal): static;

    public function getUninitialized(): string;

    public function setUninitialized(string $uninitialized): static;

    public function getChildren(): ?CollectionInterface;

    public function setChildren(?CollectionInterface $children): static;
}
