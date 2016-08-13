<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\Parsers\AliceStyleParser;
use Tests\Utils\NullDefinitionLocator;

final class AliceStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var FixtureDefinitionFactory */
    private $definitionFactory;

    /** @var AliceStyleParser */
    private $parser;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->definitionFactory = new FixtureDefinitionFactory();
        $this->parser = new AliceStyleParser($this->definitionFactory);
    }

    /** @test */
    public function simple_object()
    {
        $this->assertEquals([
            "euro" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                "isoCode" => $this->definitionFactory->scalar("EUR")
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
            "one_euro" => $this->definitionFactory->object("Tests\\Utils\\Money", [
                "amount" => $this->definitionFactory->scalar(100),
                "currency" => $this->definitionFactory->reference("currency.euro", new NullDefinitionLocator())
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
            "one_euro" => $this->definitionFactory->object("Tests\\Utils\\Money", [
                "amount" => $this->definitionFactory->scalar(100),
                "currency" => $this->definitionFactory->reference("currency.euro", new NullDefinitionLocator())
            ]),
            "two_dollars" => $this->definitionFactory->object("Tests\\Utils\\Money", [
                "amount" => $this->definitionFactory->scalar(200),
                "currency" => $this->definitionFactory->reference("currency.dollar", new NullDefinitionLocator())
            ]),
            "euro" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                "isoCode" => $this->definitionFactory->scalar("EUR")
            ]),
            "dollar" => $this->definitionFactory->object("Tests\\Utils\\Currency", [
                "isoCode" => $this->definitionFactory->scalar("USD")
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
            "definition_0" => $this->definitionFactory->object("stdClass", ["foo" => $this->definitionFactory->scalar("bar")]),
            "definition_1" => $this->definitionFactory->object("stdClass", ["foo" => $this->definitionFactory->scalar("bar")]),
            "definition_2" => $this->definitionFactory->object("stdClass", ["foo" => $this->definitionFactory->scalar("bar")]),
            "definition_3" => $this->definitionFactory->object("stdClass", ["foo" => $this->definitionFactory->scalar("bar")])
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
            "definition_option_a" => $this->definitionFactory->object("stdClass", [
                "complete" => $this->definitionFactory->scalar("option_a"),
                "partial" => $this->definitionFactory->scalar("option_a@foo")
            ]),
            "definition_option_b" => $this->definitionFactory->object("stdClass", [
                "complete" => $this->definitionFactory->scalar("option_b"),
                "partial" => $this->definitionFactory->scalar("option_b@foo")
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
            "a_person" => $this->definitionFactory->object("Tests\\Utils\\Person", [
                "setFriend" => $this->definitionFactory->methodCall([
                    $this->definitionFactory->scalar("a_friend")
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
            "a_person" => $this->definitionFactory->object("Tests\\Utils\\Person", [
                "setFriend" => $this->definitionFactory->methodCall([
                    $this->definitionFactory->reference("a_friend", new NullDefinitionLocator())
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
     * @return FixtureDefinition[]
     */
    private function parse(array $rawData)
    {
        return $this->parser->parse($rawData, new NullDefinitionLocator());
    }
}
