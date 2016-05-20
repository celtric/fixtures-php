<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;

final class ReferenceTest extends \PHPUnit_Framework_TestCase
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

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    private function fixture($fixtureIdentifier)
    {
        return (new Fixtures(__DIR__ . "/../fixtures/"))->fixture($fixtureIdentifier);
    }
}
