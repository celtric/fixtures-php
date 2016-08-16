<?php

namespace Tests\Functional;

use Celtric\Fixtures\DefinitionLocators\RegexNamespaceBasedDefinitionLocator;
use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\Fixtures;
use Celtric\Fixtures\Parsers\AliceStyleParser;
use Celtric\Fixtures\RawDataLocators\YAMLRawDataLocator;
use Celtric\Fixtures\Parsers\CeltricStyleParser;
use Tests\Utils\Currency;
use Tests\Utils\Money;

final class CrossParserTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function celtric_money_with_alice_currency()
    {
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixtures()->fixture("cross_parser_money.one_euro"));
    }

    /** @test */
    public function all_celtric_money_with_alice_currency()
    {
        $this->assertEquals([
                "one_euro" => new Money(100, new Currency("EUR")),
                "two_dollars" => new Money(200, new Currency("USD"))
        ], $this->fixtures()->namespaceFixtures("cross_parser_money"));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @return mixed
     */
    private function fixtures()
    {
        $definitionFactory = new FixtureDefinitionFactory();

        $parsers = [
            "/^alice_style\\.(.*)/" => new AliceStyleParser($definitionFactory),
            "/(.*)/" => new CeltricStyleParser($definitionFactory)
        ];

        return new Fixtures(
                new RegexNamespaceBasedDefinitionLocator(new YAMLRawDataLocator(__DIR__ . "/../fixtures/"), $parsers));
    }
}
