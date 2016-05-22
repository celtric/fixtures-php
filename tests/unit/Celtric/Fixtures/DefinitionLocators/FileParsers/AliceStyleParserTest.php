<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\Parsers\AliceStyleParser;
use Celtric\Fixtures\FixtureDefinition;

final class AliceStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function simple_object()
    {
        $this->assertEquals([
            "euro" => new FixtureDefinition("Tests\\Utils\\Currency", [
                "isoCode" => new FixtureDefinition("string", "EUR")
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
            "one_euro" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 100),
                "currency" => new FixtureDefinition("reference", "currency.euro")
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
            "one_euro" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 100),
                "currency" => new FixtureDefinition("reference", "currency.euro")
            ]),
            "two_dollars" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 200),
                "currency" => new FixtureDefinition("reference", "currency.dollar")
            ]),
            "euro" => new FixtureDefinition("Tests\\Utils\\Currency", [
                "isoCode" => new FixtureDefinition("string", "EUR")
            ]),
            "dollar" => new FixtureDefinition("Tests\\Utils\\Currency", [
                "isoCode" => new FixtureDefinition("string", "USD")
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
    public function generates_new_definitions_based_on_a_given_range()
    {
        $this->assertEquals([
            "definition_0" => new FixtureDefinition("stdClass", ["foo" => new FixtureDefinition("string", "bar")]),
            "definition_1" => new FixtureDefinition("stdClass", ["foo" => new FixtureDefinition("string", "bar")]),
            "definition_2" => new FixtureDefinition("stdClass", ["foo" => new FixtureDefinition("string", "bar")]),
            "definition_3" => new FixtureDefinition("stdClass", ["foo" => new FixtureDefinition("string", "bar")])
        ], $this->parse([
            "stdClass" => [
                "definition_{0..3}" => [
                    "foo" => "bar"
                ]
            ]
        ]));
    }

    /** @test */
    public function generates_new_definitions_based_on_a_given_list()
    {
        $this->assertEquals([
            "definition_option_a" => new FixtureDefinition("stdClass", ["option" => new FixtureDefinition("string", "option_a")]),
            "definition_option_b" => new FixtureDefinition("stdClass", ["option" => new FixtureDefinition("string", "option_b")])
        ], $this->parse([
            "stdClass" => [
                "definition_{option_a, option_b}" => [
                    "option" => "<current()>"
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
        return (new AliceStyleParser())->parse($rawData);
    }
}
