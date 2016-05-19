<?php

namespace Tests\Fixtures;

final class Currency
{
    /** @var string */
    private $isoCode;

    /**
     * @param string $isoCode
     */
    public function __construct($isoCode)
    {
        $this->isoCode = $isoCode;
    }
}
