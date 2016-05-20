<?php

namespace Tests\Functional;

final class ReferenceTest extends FunctionalTestCase
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
}
