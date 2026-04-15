<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto;

use Exterrestris\DtoFramework\Dto\Metadata\BaseDto;
use Exterrestris\DtoFramework\Serializer\Rules\NoSerialize;

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
