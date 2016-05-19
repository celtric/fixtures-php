<?php

namespace Tests\Fixtures;

final class Money
{
    /** @var int */
    private $amount;

    /** @var Currency */
    private $currency;

    /**
     * @param int $amount
     * @param Currency $currency
     */
    public function __construct($amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
