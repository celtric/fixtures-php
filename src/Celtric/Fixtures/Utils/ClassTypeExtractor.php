<?php

namespace Celtric\Fixtures\Utils;

final class ClassTypeExtractor
{
    /** @var DocBlockReader */
    private $docBlockReader;

    /**
     * @param DocBlockReader $docBlockReader
     */
    public function __construct(DocBlockReader $docBlockReader)
    {
        $this->docBlockReader = $docBlockReader;
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @return string|null
     */
    public function extractPropertyType($className, $propertyName)
    {
        if ($className === "array" || !property_exists($className, $propertyName)) {
            return null;
        }

        $type = $this->docBlockReader->getPropertyType($className, $propertyName);

        if (empty($type)) {
            return null;
        }

        $type = $this->withoutNullable($type);

        if ($this->isPrimitiveType($type) || $this->isBuiltinType($type)) {
            return $type;
        }

        $namespace = (new \ReflectionClass($className))->getNamespaceName();

        return $this->withoutNullable("{$namespace}\\{$type}");
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isPrimitiveType($type)
    {
        return in_array($type, ["int", "integer", "bool", "boolean", "string", "null"], true);
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isBuiltinType($type)
    {
        return substr($type, 0, 1) === "\\";
    }

    /**
     * @param string $type
     * @return string
     */
    private function withoutNullable($type)
    {
        return explode("|", $type)[0];
    }
}
