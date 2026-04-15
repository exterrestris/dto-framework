<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto\Dto;

use Exterrestris\DtoFramework\Dto\Dto\Metadata\BaseDto;
use Exterrestris\DtoFramework\Serialization\Rule\NoSerialize;

#[BaseDto]
abstract class AbstractProcessableDto extends AbstractDto implements ProcessableDtoInterface
{
    #[NoSerialize]
    protected ?bool $isProcessed = null;

    public function isProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): static
    {
        return $this->with(['isProcessed' => $isProcessed]);
    }
}
