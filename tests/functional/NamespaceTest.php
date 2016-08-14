<?php

namespace Tests\Functional;

final class NamespaceTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function returns_all_fixtures_defined_in_a_given_namespace()
    {
        $this->assertEquals([
            "null_value" => null,
            "integer" => 123,
            "float" => 123.456,
            "string" => "Foo",
            "boolean" => true
        ], $this->namespaceFixtures("scalar"));
    }
}
