<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;
use Tests\Utils\Currency;
use Tests\Utils\Money;

final class TypeTest extends \PHPUnit_Framework_TestCase
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

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    private function fixture($fixtureIdentifier)
    {
        return (new Fixtures(__DIR__ . "/../fixtures/"))->fixture($fixtureIdentifier);
    }
}
