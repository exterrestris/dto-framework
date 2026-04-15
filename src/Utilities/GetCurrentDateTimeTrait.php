<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Utilities;

use DateTimeImmutable;

/**
 * Utility trait to allow code that requires the current date/time to be tested by setting a known date/time
 */
trait GetCurrentDateTimeTrait
{
    private ?DateTimeImmutable $_now;

    /**
     * @param ?DateTimeImmutable $now
     * @return void
     * @internal Facilitiates testing by allowing 'now' to be overridden
     */
    public function setNowDateTime(?DateTimeImmutable $now): void {
        $this->_now = $now;
    }

    protected function getNowDateTime(): DateTimeImmutable
    {
        if (!$this->_now) {
            $this->_now = new DateTimeImmutable();
        }

        return $this->_now;
    }
}
