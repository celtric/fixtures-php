<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\Parsers\CeltricStyleParser;
use Celtric\Fixtures\FixtureDefinition;

final class CeltricStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function null_value()
    {
        $this->assertEquals([
            "null_value" => new FixtureDefinition("null", null)
        ], $this->parse([
            "null_value" => null
        ]));
    }

    /** @test */
    public function empty_array()
    {
        $this->assertEquals([
            "empty_array" => new FixtureDefinition("array", [])
        ], $this->parse([
            "empty_array<array>" => null
        ]));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "scalar_values" => new FixtureDefinition("array", [
                "int" => new FixtureDefinition("integer", 123),
                "float" => new FixtureDefinition("float", 123.456),
                "string" => new FixtureDefinition("string", "Foo"),
                "bool" => new FixtureDefinition("boolean", true)
            ])
        ], $this->parse([
            "scalar_values" => [
                "int" => 123,
                "float" => 123.456,
                "string" => "Foo",
                "bool" => true
            ]
        ]));
    }

    /** @test */
    public function multidimensional_array()
    {
        $this->assertEquals([
            "multidimensional_array" => new FixtureDefinition("array", [
                "foo" => new FixtureDefinition("string", "bar"),
                "one" => new FixtureDefinition("array", [
                    "two" => new FixtureDefinition("array", [
                        "three" => new FixtureDefinition("string", "foobar")
                    ])
                ])
            ])
        ], $this->parse([
            "multidimensional_array" => [
                "foo" => "bar",
                "one" => [
                    "two" => [
                        "three" => "foobar"
                    ]
                ]
            ]
        ]));
    }

    /** @test */
    public function typed_array()
    {
        $this->assertEquals([
            "typed_array" => new FixtureDefinition("array", [
                "foo" => new FixtureDefinition("string", "bar")
            ])
        ], $this->parse([
            "typed_array<array>" => [
                "foo" => "bar"
            ]
        ]));
    }

    /** @test */
    public function multidimensional_typed_array()
    {
        $this->assertEquals([
            "multidimensional_typed_array" => new FixtureDefinition("array", [
                "foo" => new FixtureDefinition("string", "bar"),
                "one" => new FixtureDefinition("array", [
                    "two" => new FixtureDefinition("array", [
                        "three" => new FixtureDefinition("string", "foobar")
                    ])
                ])
            ])
        ], $this->parse([
            "multidimensional_typed_array<array>" => [
                "foo" => "bar",
                "one<array>" => [
                    "two<array>" => [
                        "three" => "foobar"
                    ]
                ]
            ]
        ]));
    }

    /** @test */
    public function simple_object()
    {
        $this->assertEquals([
            "euro" => new FixtureDefinition("Tests\\Utils\\Currency", [
                "isoCode" => new FixtureDefinition("string", "EUR")
            ])
        ], $this->parse([
            "euro<Tests\\Utils\\Currency>" => [
                "isoCode" => "EUR"
            ]
        ]));
    }

    /** @test */
    public function complex_object()
    {
        $this->assertEquals([
            "one_euro" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 100),
                "currency" => new FixtureDefinition("Tests\\Utils\\Currency", [
                    "isoCode" => new FixtureDefinition("string", "EUR")
                ])
            ])
        ], $this->parse([
            "one_euro<Tests\\Utils\\Money>" => [
                "amount" => 100,
                "currency<Tests\\Utils\\Currency>" => [
                    "isoCode" => "EUR"
                ]
            ]
        ]));
    }

    /** @test */
    public function root_type()
    {
        $this->assertEquals([
            "one_euro" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 100),
                "currency" => new FixtureDefinition("Tests\\Utils\\Currency", [
                    "isoCode" => new FixtureDefinition("string", "EUR")
                ])
            ])
        ], $this->parse([
            "root_type" => "Tests\\Utils\\Money",
            "one_euro" => [
                "amount" => 100,
                "currency<Tests\\Utils\\Currency>" => [
                    "isoCode" => "EUR"
                ]
            ]
        ]));
    }

    /** @test */
    public function simple_reference()
    {
        $this->assertEquals([
            "same_file_array" => new FixtureDefinition("array", [
                "foo" => new FixtureDefinition("reference", "references.bar")
            ])
        ], $this->parse([
            "same_file_array" => [
                "foo" => "@references.bar"
            ]
        ]));
    }

    /** @test */
    public function complex_references()
    {
        $this->assertEquals([
            "ref" => new FixtureDefinition("array", [
                "ref2" => new FixtureDefinition("reference", "references.ref2"),
                "name" => new FixtureDefinition("reference", "references.name"),
                "ref" => new FixtureDefinition("array", [
                    "ref2" => new FixtureDefinition("reference", "references.ref2"),
                    "name" => new FixtureDefinition("reference", "references.name"),
                    "ref" => new FixtureDefinition("array", [
                        "ref2" => new FixtureDefinition("reference", "references.ref2"),
                        "name" => new FixtureDefinition("reference", "references.name")
                    ])
                ])
            ])
        ], $this->parse([
            "ref" => [
                "ref2" => "@references.ref2",
                "name" => "@references.name",
                "ref" => [
                    "ref2" => "@references.ref2",
                    "name" => "@references.name",
                    "ref" => [
                        "ref2" => "@references.ref2",
                        "name" => "@references.name"
                    ]
                ]
            ]
        ]));
    }

    /** @test */
    public function method_call()
    {
        $this->assertEquals([
            "a_person" => new FixtureDefinition("Tests\\Utils\\Person", [
                "setFriend" => new FixtureDefinition("method_call", [
                    new FixtureDefinition("string", "a_friend")
                ])
            ]),
            "another_person" => new FixtureDefinition("Tests\\Utils\\Person", [
                "setFriend" => new FixtureDefinition("method_call", [
                    new FixtureDefinition("string", "a_friend")
                ])
            ])
        ], $this->parse([
            "a_person<Tests\\Utils\\Person>" => [
                "setFriend" => "a_friend"
            ],
            "another_person<Tests\\Utils\\Person>" => [
                "setFriend" => ["a_friend"]
            ]
        ]));
    }

    /** @test */
    public function method_call_with_reference()
    {
        $this->assertEquals([
            "a_person" => new FixtureDefinition("Tests\\Utils\\Person", [
                "setFriend" => new FixtureDefinition("method_call", [
                    new FixtureDefinition("reference", "a_friend")
                ])
            ])
        ], $this->parse([
            "a_person<Tests\\Utils\\Person>" => [
                "setFriend" => [
                    "@a_friend"
                ]
            ]
        ]));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param array $rawData
     * @return FixtureDefinition[]
     */
    private function parse(array $rawData)
    {
        return (new CeltricStyleParser())->parse($rawData);
    }
}
