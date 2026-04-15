<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Tests\Mocks\Dto;

use Exterrestris\DtoFramework\Dto\DtoInterface;

interface MockNamedDtoInterface extends DtoInterface
{
    public function getName(): string;

    public function setName(string $name): static;

    public function getTitle(): ?string;

    public function setTitle(?string $title): static;
}
