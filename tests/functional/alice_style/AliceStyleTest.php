<?php

namespace Tests\Functional\alice_style;

use Tests\Functional\AliceStyleFunctionalTestCase;
use Tests\Utils\Currency;
use Tests\Utils\Money;
use Tests\Utils\Person;

final class AliceStyleTest extends AliceStyleFunctionalTestCase
{
    /** @test */
    public function complex_objects_spanning_different_namespaces()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->loadFixture("alice_style.money.one_euro"));
        $this->assertEquals(new Money(200, new Currency("USD")), $this->loadFixture("alice_style.money.two_dollars"));
    }

    /** @test */
    public function method_calls()
    {
        $person = new Person("Ricard", 30);
        $person->setFriend(new Person("Phteven", 8));
        $person->setCoordinates(1, 2, 3);

        $this->assertEquals($person, $this->loadFixture("alice_style.people.ricard_with_late_friend"));
    }
}
