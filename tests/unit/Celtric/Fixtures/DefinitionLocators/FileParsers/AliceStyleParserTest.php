<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\Parsers\AliceStyleParser;
use Celtric\Fixtures\FixtureDefinition as Definition;

final class AliceStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function simple_object()
    {
        $this->assertEquals([
            "euro" => Definition::object("Tests\\Utils\\Currency", [
                "isoCode" => Definition::native("EUR")
            ])
        ], $this->parse([
            "Tests\\Utils\\Currency" => [
                "euro" => [
                    "isoCode" => "EUR"
                ]
            ]
        ]));
    }

    /** @test */
    public function reference()
    {
        $this->assertEquals([
            "one_euro" => Definition::object("Tests\\Utils\\Money", [
                "amount" => Definition::native(100),
                "currency" => Definition::reference("currency.euro")
            ])
        ], $this->parse([
            "Tests\\Utils\\Money" => [
                "one_euro" => [
                    "amount" => 100,
                    "currency" => "@currency.euro"
                ]
            ]
        ]));
    }

    /** @test */
    public function multiple_types()
    {
        $this->assertEquals([
            "one_euro" => Definition::object("Tests\\Utils\\Money", [
                "amount" => Definition::native(100),
                "currency" => Definition::reference("currency.euro")
            ]),
            "two_dollars" => Definition::object("Tests\\Utils\\Money", [
                "amount" => Definition::native(200),
                "currency" => Definition::reference("currency.dollar")
            ]),
            "euro" => Definition::object("Tests\\Utils\\Currency", [
                "isoCode" => Definition::native("EUR")
            ]),
            "dollar" => Definition::object("Tests\\Utils\\Currency", [
                "isoCode" => Definition::native("USD")
            ])
        ], $this->parse([
            "Tests\\Utils\\Money" => [
                "one_euro" => [
                    "amount" => 100,
                    "currency" => "@currency.euro"
                ],
                "two_dollars" => [
                    "amount" => 200,
                    "currency" => "@currency.dollar"
                ]
            ],
            "Tests\\Utils\\Currency" => [
                "euro" => [
                    "isoCode" => "EUR"
                ],
                "dollar" => [
                    "isoCode" => "USD"
                ]
            ]
        ]));
    }

    /** @test */
    public function range()
    {
        $this->assertEquals([
            "definition_0" => Definition::object("stdClass", ["foo" => Definition::native("bar")]),
            "definition_1" => Definition::object("stdClass", ["foo" => Definition::native("bar")]),
            "definition_2" => Definition::object("stdClass", ["foo" => Definition::native("bar")]),
            "definition_3" => Definition::object("stdClass", ["foo" => Definition::native("bar")])
        ], $this->parse([
            "stdClass" => [
                "definition_{0..3}" => [
                    "foo" => "bar"
                ]
            ]
        ]));
    }

    /** @test */
    public function custom_list()
    {
        $this->assertEquals([
            "definition_option_a" => Definition::object("stdClass", [
                "complete" => Definition::native("option_a"),
                "partial" => Definition::native("option_a@foo")
            ]),
            "definition_option_b" => Definition::object("stdClass", [
                "complete" => Definition::native("option_b"),
                "partial" => Definition::native("option_b@foo")
            ])
        ], $this->parse([
            "stdClass" => [
                "definition_{option_a, option_b}" => [
                    "complete" => "<current()>",
                    "partial" => "<current()>@foo"
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
            ])
        ], $this->parse([
            "Tests\\Utils\\Person" => [
                "a_person" => [
                    "setFriend" => [
                        "a_friend"
                    ]
                ]
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
            "Tests\\Utils\\Person" => [
                "a_person" => [
                    "setFriend" => [
                        "@a_friend"
                    ]
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
        return (new AliceStyleParser())->parse($rawData);
    }
}
