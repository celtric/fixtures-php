<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\Parsers\CeltricStyleParser;
use Celtric\Fixtures\FixtureDefinition as Definition;

final class CeltricStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function null_value()
    {
        $this->assertEquals([
            "null_value" => Definition::native(null)
        ], $this->parse([
            "null_value" => null
        ]));
    }

    /** @test */
    public function empty_array()
    {
        $this->assertEquals([
            "empty_array" => Definition::arr([])
        ], $this->parse([
            "empty_array<array>" => null
        ]));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "scalar_values" => Definition::arr([
                "int" => Definition::native(123),
                "float" => Definition::native(123.456),
                "string" => Definition::native("Foo"),
                "bool" => Definition::native(true)
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
            "multidimensional_array" => Definition::arr([
                "foo" => Definition::native("bar"),
                "one" => Definition::arr([
                    "two" => Definition::arr([
                        "three" => Definition::native("foobar")
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
            "typed_array" => Definition::arr([
                "foo" => Definition::native("bar")
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
            "multidimensional_typed_array" => Definition::arr([
                "foo" => Definition::native("bar"),
                "one" => Definition::arr([
                    "two" => Definition::arr([
                        "three" => Definition::native("foobar")
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
            "euro" => Definition::object("Tests\\Utils\\Currency", [
                "isoCode" => Definition::native("EUR")
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
            "one_euro" => Definition::object("Tests\\Utils\\Money", [
                "amount" => Definition::native(100),
                "currency" => Definition::object("Tests\\Utils\\Currency", [
                    "isoCode" => Definition::native("EUR")
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
            "one_euro" => Definition::object("Tests\\Utils\\Money", [
                "amount" => Definition::native(100),
                "currency" => Definition::object("Tests\\Utils\\Currency", [
                    "isoCode" => Definition::native("EUR")
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
            "same_file_array" => Definition::arr([
                "foo" => Definition::reference("references.bar")
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
            "ref" => Definition::arr([
                "ref2" => Definition::reference("references.ref2"),
                "name" => Definition::reference("references.name"),
                "ref" => Definition::arr([
                    "ref2" => Definition::reference("references.ref2"),
                    "name" => Definition::reference("references.name"),
                    "ref" => Definition::arr([
                        "ref2" => Definition::reference("references.ref2"),
                        "name" => Definition::reference("references.name")
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
            "a_person" => Definition::object("Tests\\Utils\\Person", [
                "setFriend" => Definition::methodCall([
                    Definition::native("a_friend")
                ])
            ]),
            "another_person" => Definition::object("Tests\\Utils\\Person", [
                "setFriend" => Definition::methodCall([
                    Definition::native("a_friend")
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
            "a_person" => Definition::object("Tests\\Utils\\Person", [
                "setFriend" => Definition::methodCall([
                    Definition::reference("a_friend")
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
     * @return Definition[]
     */
    private function parse(array $rawData)
    {
        return (new CeltricStyleParser())->parse($rawData);
    }
}
