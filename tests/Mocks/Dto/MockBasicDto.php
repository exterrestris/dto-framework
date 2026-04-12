<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Validation\Rules\NotEmpty;

class MockBasicDto extends AbstractDto
{
    #[NotEmpty]
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        return $this->with('name', $name);
    }
}
