<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\Dto\AbstractDto;
use Exterrestris\DtoFramework\Validation\Rule\NotEmpty;

class MockBasicDto extends AbstractDto implements MockNamedDtoInterface
{
    #[NotEmpty]
    protected string $name;
    protected ?string $title = null;

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
}
