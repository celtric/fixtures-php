<?php

namespace Tests\Functional;

final class ScalarTest extends FunctionalTestCase
{
    /** @test */
    public function integer()
    {
        $this->assertSame(123, $this->fixture("scalar.integer"));
    }

    /** @test */
    public function float()
    {
        $this->assertSame(123.456, $this->fixture("scalar.float"));
    }

    /** @test */
    public function string()
    {
        $this->assertSame("Foo", $this->fixture("scalar.string"));
    }

    /** @test */
    public function boolean()
    {
        $this->assertSame(true, $this->fixture("scalar.boolean"));
    }
}
