<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class ScalarFixture implements FixtureDefinition
{
    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (!is_scalar($value)) {
            throw new \RuntimeException("Value must be scalar.");
        }
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        return $this->value;
    }
}
