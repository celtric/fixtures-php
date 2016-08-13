<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class NativeFixture extends FixtureDefinition
{
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $type = strtolower(gettype($value));

        if ($type === "double") {
            $type = "float";
        }

        parent::__construct($type, $value);
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        return $this->data();
    }
}
