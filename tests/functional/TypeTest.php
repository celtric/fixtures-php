<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;
use Tests\Fixtures\Currency;
use Tests\Fixtures\Money;

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
