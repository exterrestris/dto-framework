<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks;

use Exterrestris\DtoFramework\Dto\AbstractDto;
use Exterrestris\DtoFramework\Validator\Rules\NotEmpty;

class TestDto extends AbstractDto
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
