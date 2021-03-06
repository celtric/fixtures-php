<?php

namespace Tests\Functional;

use Tests\Utils\Currency;
use Tests\Utils\Money;
use Tests\Utils\Person;

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

    /** @test */
    public function method_calls()
    {
        $person = new Person("Ricard", 30);
        $person->setFriend(new Person("Phteven", 8));
        $person->setCoordinates(1, 2, 3);

        $this->assertEquals($person, $this->fixture("company.people.ricard_with_late_friend"));
    }

    /** @test */
    public function derived_property_type()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixture("money.one_euro_without_currency_type"));
    }

    /** @test */
    public function uses_constructor_if_no_properties_are_defined()
    {
        $this->assertEquals(new \DateTimeImmutable("2016-09-12 10:19:00"), $this->fixture("company.people.person_with_birthday")->getBirthday());
    }
}
