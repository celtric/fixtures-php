<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class ArrayFixture extends FixtureDefinition
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct("array", $data);
    }
}
