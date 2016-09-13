<?php

namespace Tests\Functional;

final class ScalarTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function null()
    {
        $this->assertNull($this->fixture("scalar.null_value"));
    }

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
    public function empty_string()
    {
        $this->assertSame("", $this->fixture("scalar.empty_string"));
    }

    /** @test */
    public function boolean()
    {
        $this->assertTrue($this->fixture("scalar.boolean"));
    }
}
