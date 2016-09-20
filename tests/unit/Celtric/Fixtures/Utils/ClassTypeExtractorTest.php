<?php

namespace Tests\Unit\Celtric\Fixtures\Utils;

use Celtric\Fixtures\Utils\ClassTypeExtractor;
use Celtric\Fixtures\Utils\DocBlockReader;
use Prophecy\Argument;
use Tests\Utils\Currency;
use Tests\Utils\Money;
use Tests\Utils\Person;

final class ClassTypeExtractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var mixed */
    private $docBlockReader;

    /** @test */
    public function cannot_extract_type_from_array()
    {
        $this->assertNull($this->extract("array", null));
    }

    /** @test */
    public function cannot_extract_type_from_nonexisting_class_property()
    {
        $this->assertNull($this->extract(Person::class, "foo"));
    }

    /** @test */
    public function cannot_extract_type_if_property_does_not_have_docblock()
    {
        $this->assertNull($this->extractWithFakeType(null));
    }

    /** @test */
    public function can_extract_primitive_types_from_property_docblock()
    {
        $this->assertEquals("int", $this->extractWithFakeType("int"));
        $this->assertEquals("integer", $this->extractWithFakeType("integer"));
        $this->assertEquals("bool", $this->extractWithFakeType("bool"));
        $this->assertEquals("boolean", $this->extractWithFakeType("boolean"));
        $this->assertEquals("string", $this->extractWithFakeType("string"));
        $this->assertEquals("null", $this->extractWithFakeType("null"));
    }

    /** @test */
    public function can_extract_builtin_types_from_property_docblock()
    {
        $this->assertEquals("\\DateTimeImmutable", $this->extractWithFakeType("\\DateTimeImmutable"));
    }

    /** @test */
    public function can_extract_namespaced_types_from_property_docblock()
    {
        $this->docBlockReaderWillReturn("Currency");

        $this->assertEquals(Currency::class, $this->extract(Money::class, "currency"));
    }

    /** @test */
    public function can_extract_nullable_custom_types()
    {
        $this->docBlockReaderWillReturn("Person|null");

        $this->assertEquals(Person::class, $this->extract(Person::class, "friend"));
    }

    /** @test */
    public function can_extract_nullable_builtin_types()
    {
        $this->assertEquals("\\DateTimeImmutable", $this->extractWithFakeType("\\DateTimeImmutable|null"));
    }

    /** @test */
    public function can_extract_nullable_primitive_types()
    {
        $this->assertEquals("int", $this->extractWithFakeType("int|null"));
        $this->assertEquals("integer", $this->extractWithFakeType("integer|null"));
        $this->assertEquals("bool", $this->extractWithFakeType("bool|null"));
        $this->assertEquals("boolean", $this->extractWithFakeType("boolean|null"));
        $this->assertEquals("string", $this->extractWithFakeType("string|null"));
        $this->assertEquals("null", $this->extractWithFakeType("null|null"));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->docBlockReader = $this->prophesize(DocBlockReader::class);
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @return string|null
     */
    private function extract($className, $propertyName)
    {
        return (new ClassTypeExtractor($this->docBlockReader->reveal()))->extractPropertyType($className, $propertyName);
    }

    /**
     * @param string $type
     */
    private function docBlockReaderWillReturn($type)
    {
        $this->docBlockReader->getPropertyType(Argument::cetera())->willReturn($type);
    }

    /**
     * @param string $fakeType
     * @return string|null
     */
    private function extractWithFakeType($fakeType)
    {
        $this->docBlockReaderWillReturn($fakeType);

        return $this->extract(Person::class, "id");
    }
}
