<?php

namespace tests\unit\Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\DefinitionLocators\FileParsers\CeltricStyleParser;
use Celtric\Fixtures\FixtureDefinition;

final class CeltricStyleParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function null_value()
    {
        $this->assertEquals([
            "null_value" => new FixtureDefinition("null", null)
        ], $this->parseFixture("null_value:"));
    }

    /** @test */
    public function empty_array()
    {
        $this->assertEquals([
            "empty_array" => new FixtureDefinition("array", [])
        ], $this->parseFixture("empty_array<array>:"));
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
        ], $this->parseFixture(<<<YAML
scalar_values:
    int: 123
    float: 123.456
    string: "Foo"
    bool: true
YAML
));
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
        ], $this->parseFixture(<<<YAML
multidimensional_array:
    foo: "bar"
    one:
        two:
            three: "foobar"
YAML
));
    }

    /** @test */
    public function typed_array()
    {
        $this->assertEquals([
            "typed_array" => new FixtureDefinition("array", [
                "foo" => new FixtureDefinition("string", "bar")
            ])
        ], $this->parseFixture(<<<YAML
typed_array<array>:
    foo: "bar"
YAML
));
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
        ], $this->parseFixture(<<<YAML
multidimensional_typed_array<array>:
    foo: "bar"
    one<array>:
        two<array>:
            three: "foobar"
YAML
));
    }

    /** @test */
    public function simple_type()
    {
        $this->assertEquals([
            "euro" => new FixtureDefinition("Tests\\Utils\\Currency", [
                "isoCode" => new FixtureDefinition("string", "EUR")
            ])
        ], $this->parseFixture(<<<YAML
euro<Tests\Utils\Currency>:
    isoCode: "EUR"
YAML
));
    }

    /** @test */
    public function complex_type()
    {
        $this->assertEquals([
            "one_euro" => new FixtureDefinition("Tests\\Utils\\Money", [
                "amount" => new FixtureDefinition("integer", 100),
                "currency" => new FixtureDefinition("Tests\\Utils\\Currency", [
                    "isoCode" => new FixtureDefinition("string", "EUR")
                ])
            ])
        ], $this->parseFixture(<<<YAML
one_euro<Tests\Utils\Money>:
    amount: 100
    currency<Tests\Utils\Currency>:
        isoCode: "EUR"
YAML
));
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
        ], $this->parseFixture(<<<YAML
root_type: Tests\Utils\Money

one_euro:
    amount: 100
    currency<Tests\Utils\Currency>:
        isoCode: "EUR"
YAML
));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param string $rawData
     * @return FixtureDefinition[]
     */
    private function parseFixture($rawData)
    {
        return (new CeltricStyleParser())->parse($rawData);
    }
}
