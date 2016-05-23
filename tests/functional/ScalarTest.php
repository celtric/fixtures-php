<?php

namespace Tests\Functional;

final class ScalarTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function null()
    {
        $this->assertNull($this->loadFixture("scalar.null_value"));
    }

    /** @test */
    public function integer()
    {
        $this->assertSame(123, $this->loadFixture("scalar.integer"));
    }

    /** @test */
    public function float()
    {
        $this->assertSame(123.456, $this->loadFixture("scalar.float"));
    }

    /** @test */
    public function string()
    {
        $this->assertSame("Foo", $this->loadFixture("scalar.string"));
    }

    /** @test */
    public function boolean()
    {
        $this->assertTrue($this->loadFixture("scalar.boolean"));
    }
}
