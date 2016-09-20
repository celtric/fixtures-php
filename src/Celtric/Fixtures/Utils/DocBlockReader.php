<?php

namespace Celtric\Fixtures\Utils;

interface DocBlockReader
{
    /**
     * @param string $className
     * @param string $propertyName
     * @return string|null
     */
    public function getPropertyType($className, $propertyName);
}
