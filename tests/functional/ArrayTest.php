<?php

namespace Tests\Functional;

final class ArrayTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function empty_array()
    {
        $this->assertEquals([], $this->loadFixture("arrays.empty"));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "int" => 123,
            "float" => 123.456,
            "string" => "Foo",
            "bool" => true
        ], $this->loadFixture("arrays.scalar_values"));
    }

    /** @test */
    public function lists()
    {
        $this->assertEquals([1, 2, 3, [4, 5, [6, 7]]], $this->loadFixture("arrays.lists"));
    }

    /** @test */
    public function multidimensional()
    {
        $this->assertEquals([
            "foo" => "bar",
            "one" => [
                "two" => [
                    "three" => "foobar"
                ]
            ]
        ], $this->loadFixture("arrays.multidimensional"));
    }

    /** @test */
    public function typed_array()
    {
        $this->assertEquals([
            "foo" => "bar"
        ], $this->loadFixture("arrays.typed_array"));
    }

    /** @test */
    public function multidimensional_typed_array()
    {
        $this->assertEquals([
            "foo" => "bar",
            "one" => [
                "two" => [
                    "three" => "foobar"
                ]
            ]
        ], $this->loadFixture("arrays.multidimensional_typed_array"));
    }
}
