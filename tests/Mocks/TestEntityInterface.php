<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks;

use Exterrestris\DtoFramework\Dto\Collection\CollectionInterface;
use Exterrestris\DtoFramework\Dto\ProcessableDtoInterface;

interface TestEntityInterface extends ProcessableDtoInterface
{
    public function getName(): string;

    public function setName(string $name): TestEntity;

    public function getTitle(): ?string;

    public function setTitle(?string $title): TestEntity;

    public function isInternal(): bool;

    public function setInternal(bool $internal): TestEntity;

    public function getUninitialized(): string;

    public function setUninitialized(string $uninitialized): TestEntity;

    public function getChildren(): ?CollectionInterface;

    public function setChildren(?CollectionInterface $children): static;
}
