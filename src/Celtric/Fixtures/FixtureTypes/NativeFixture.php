<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class NativeFixture extends FixtureDefinition
{
    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        $type = strtolower(gettype($data));

        if ($type === "double") {
            $type = "float";
        }

        parent::__construct($type, $data);
    }
}
