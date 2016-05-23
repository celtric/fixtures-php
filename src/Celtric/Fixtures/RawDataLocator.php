<?php

namespace Celtric\Fixtures;

interface RawDataLocator
{
    /**
     * @param string $namespace
     * @return array
     */
    public function retrieveRawData($namespace);
}
