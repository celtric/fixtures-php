<?php

namespace Tests\Functional;

use Tests\Utils\Currency;
use Tests\Utils\Money;
use Tests\Utils\Person;

final class ReferenceTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function same_file_array()
    {
        $this->assertEquals([
            "foo" => [
                "fullName" => "FooBar"
            ]
        ], $this->fixture("references.same_file_array"));
    }

    /** @test */
    public function same_file_multidimensional_array()
    {
        $this->assertEquals([
            "ref2" => [
                "name" => "Ricard",
                "ref" => [
                    "name" => "Ricard",
                    "ref" => [
                        "name" => "Ricard"
                    ]
                ]
            ],
            "name" => "Ricard",
            "ref" => [
                "ref2" => [
                    "name" => "Ricard",
                    "ref" => [
                        "name" => "Ricard",
                        "ref" => [
                            "name" => "Ricard"
                        ]
                    ]
                ],
                "name" => "Ricard",
                "ref" => [
                    "ref2" => [
                        "name" => "Ricard",
                        "ref" => [
                            "name" => "Ricard",
                            "ref" => [
                                "name" => "Ricard"
                            ]
                        ]
                    ],
                    "name" => "Ricard"
                ]
            ]
        ], $this->fixture("references.ref"));
    }

    /** @test */
    public function multiple_files_inside_same_namespace()
    {
        $this->assertEquals([
            "person" => new Person("Ricard", 30),
            "balance" => new Money(100, new Currency("EUR"))
        ], $this->fixture("references.external_file_different_namespaces"));
    }

    /** @test */
    public function reference_property()
    {
        $this->assertEquals("Ricard", $this->fixture("references.reference_property")['name']);
    }
}
