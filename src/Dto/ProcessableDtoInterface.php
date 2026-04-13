<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Dto;

use Exterrestris\DtoFramework\Dto\Metadata\BaseDto;

/**
 * Processable DTO interface
 *
 * Extending {@link AbstractProcessableDto} rather than implementing this interface directly is recommended.
 *
 * @inheritDoc
 */
#[BaseDto]
interface ProcessableDtoInterface extends DtoInterface
{
    public function isProcessed(): ?bool;

    public function setIsProcessed(?bool $isProcessed): static;
}
