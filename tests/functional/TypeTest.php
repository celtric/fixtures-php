<?php

namespace Tests\Functional;

use Tests\Utils\Currency;
use Tests\Utils\Money;

final class TypeTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function simple_object()
    {
        $this->assertEquals(new Currency("EUR"), $this->fixture("currency.euro"));
    }

    /** @test */
    public function object_with_typed_fields()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixture("money.one_euro"));
    }

    /** @test */
    public function object_collections()
    {
        $this->assertEquals([
            "euros" => [
                "one" => new Money(100, new Currency("EUR")),
                "two" => new Money(200, new Currency("EUR"))
            ],
            "dollars" => [
                "one" => new Money(100, new Currency("USD")),
                "two" => new Money(200, new Currency("USD"))
            ]
        ], $this->fixture("money.many_moneys"));
    }

    /** @test */
    public function root_type()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixture("money_root_typed.one_euro"));
    }
}
