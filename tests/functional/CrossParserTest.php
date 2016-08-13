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
        $this->assertEquals(new Money(100, new Currency("EUR")), $this->fixture("cross_parser_money.one_euro"));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    private function fixture($fixtureIdentifier)
    {
        $definitionFactory = new FixtureDefinitionFactory();

        $parsers = [
            "/^alice_style\\.(.*)/" => new AliceStyleParser($definitionFactory),
            "/(.*)/" => new CeltricStyleParser($definitionFactory)
        ];

        $fixtures = new Fixtures(
                new RegexNamespaceBasedDefinitionLocator(new YAMLRawDataLocator(__DIR__ . "/../fixtures/"), $parsers));

        return $fixtures->fixture($fixtureIdentifier);
    }
}
