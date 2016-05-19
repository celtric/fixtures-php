<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;

final class ArrayTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function empty_array()
    {
        $this->assertEquals([], $this->fixture('arrays.empty'));
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
