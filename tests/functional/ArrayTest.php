<?php

namespace Tests\Functional;

final class ArrayTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function empty_array()
    {
        $this->assertEquals([], $this->fixture("arrays.empty"));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "int" => 123,
            "float" => 123.456,
            "string" => "Foo",
            "bool" => true
        ], $this->fixture("arrays.scalar_values"));
    }

    /** @test */
    public function lists()
    {
        $this->assertEquals([1, 2, 3, [4, 5, [6, 7]]], $this->fixture("arrays.lists"));
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
        ], $this->fixture("arrays.multidimensional"));
    }

    /** @test */
    public function typed_array()
    {
        $this->assertEquals([
            "foo" => "bar"
        ], $this->fixture("arrays.typed_array"));
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
        ], $this->fixture("arrays.multidimensional_typed_array"));
    }
}
