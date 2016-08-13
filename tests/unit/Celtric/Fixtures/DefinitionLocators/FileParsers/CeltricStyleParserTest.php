<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\Parsers\CeltricStyleParser;

final class CeltricStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var FixtureDefinitionFactory */
    private $definitionFactory;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->definitionFactory = new FixtureDefinitionFactory();
    }

    /** @test */
    public function null_value()
    {
        $this->assertEquals([
            "null_value" => $this->definitionFactory->native(null)
        ], $this->parse([
            "null_value" => null
        ]));
    }

    /** @test */
    public function empty_array()
    {
        $this->assertEquals([
            "empty_array" => $this->definitionFactory->arr([])
        ], $this->parse([
            "empty_array<array>" => null
        ]));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "scalar_values" => $this->definitionFactory->arr([
                "int" => $this->definitionFactory->native(123),
                "float" => $this->definitionFactory->native(123.456),
                "string" => $this->definitionFactory->native("Foo"),
                "bool" => $this->definitionFactory->native(true)
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
            "multidimensional_array" => $this->definitionFactory->arr([
                "foo" => $this->definitionFactory->native("bar"),
                "one" => $this->definitionFactory->arr([
                    "two" => $this->definitionFactory->arr([
                        "three" => $this->definitionFactory->native("foobar")
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
            "typed_array" => $this->definitionFactory->arr([
                "foo" => $this->definitionFactory->native("bar")
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
            "multidimensional_typed_array" => $this->definitionFactory->arr([
                "foo" => $this->definitionFactory->native("bar"),
                "one" => $this->definitionFactory->arr([
                    "two" => $this->definitionFactory->arr([
                        "three" => $this->definitionFactory->native("foobar")
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
            "euro" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                "isoCode" => $this->definitionFactory->native("EUR")
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
            "one_euro" => $this->definitionFactory->object("Tests\\Utils\\Money", [
                "amount" => $this->definitionFactory->native(100),
                "currency" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                    "isoCode" => $this->definitionFactory->native("EUR")
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
            "one_euro" => $this->definitionFactory->object("Tests\\Utils\\Money", [
                "amount" => $this->definitionFactory->native(100),
                "currency" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                    "isoCode" => $this->definitionFactory->native("EUR")
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
            "same_file_array" => $this->definitionFactory->arr([
                "foo" => $this->definitionFactory->reference("references.bar")
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
            "ref" => $this->definitionFactory->arr([
                "ref2" => $this->definitionFactory->reference("references.ref2"),
                "name" => $this->definitionFactory->reference("references.name"),
                "ref" => $this->definitionFactory->arr([
                    "ref2" => $this->definitionFactory->reference("references.ref2"),
                    "name" => $this->definitionFactory->reference("references.name"),
                    "ref" => $this->definitionFactory->arr([
                        "ref2" => $this->definitionFactory->reference("references.ref2"),
                        "name" => $this->definitionFactory->reference("references.name")
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
            "a_person" => $this->definitionFactory->object("Tests\\Utils\\Person", [
                "setFriend" => $this->definitionFactory->methodCall([
                    $this->definitionFactory->native("a_friend")
                ])
            ]),
            "another_person" => $this->definitionFactory->object("Tests\\Utils\\Person", [
                "setFriend" => $this->definitionFactory->methodCall([
                    $this->definitionFactory->native("a_friend")
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
            "a_person" => $this->definitionFactory->object("Tests\\Utils\\Person", [
                "setFriend" => $this->definitionFactory->methodCall([
                    $this->definitionFactory->reference("a_friend")
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
