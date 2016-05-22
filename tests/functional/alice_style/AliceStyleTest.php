<?php

namespace Tests\Functional\alice_style;

use Tests\Functional\AliceStyleFunctionalTestCase;
use Tests\Utils\Currency;
use Tests\Utils\Money;

final class AliceStyleTest extends AliceStyleFunctionalTestCase
{
    /** @test */
    public function complex_objects_spanning_different_namespaces()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixture("alice_style.money.one_euro"));
        $this->assertEquals(new Money(200, new Currency("USD")), $this->fixture("alice_style.money.two_dollars"));
    }
}
