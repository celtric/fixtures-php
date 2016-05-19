<?php

namespace Tests\Utils;

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
