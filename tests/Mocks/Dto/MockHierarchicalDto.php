<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Dto\Attributes\CollectionType;
use Exterrestris\DtoFramework\Dto\Collection\Collection;
use Exterrestris\DtoFramework\Validator\Rules\NotEmpty;
use Exterrestris\DtoFramework\Validator\Rules\ValidCollection;
use Exterrestris\DtoFramework\Validator\Rules\ValidDto;

class MockHierarchicalDto extends AbstractDto
{
    #[NotEmpty]
    protected string $name;
    #[ValidDto]
    protected ?MockHierarchicalDto $parent = null;
    #[ValidCollection]
    #[CollectionType(MockHierarchicalDto::class)]
    protected ?Collection $children = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        return $this->with('name', $name);
    }

    public function getParent(): ?MockHierarchicalDto
    {
        return $this->parent;
    }

    public function setParent(?MockHierarchicalDto $parent): MockHierarchicalDto
    {
        return $this->with('parent', $parent);
    }

    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    public function setChildren(?Collection $children): MockHierarchicalDto
    {
        return $this->with('children', $children);
    }
}
