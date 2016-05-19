<?php

namespace Tests\Unit\Celtric\Fixtures;

use Celtric\Fixtures\Foo;

final class FooTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function returns_true()
    {
        $this->assertTrue((new Foo())->returnTrue());
    }
}
