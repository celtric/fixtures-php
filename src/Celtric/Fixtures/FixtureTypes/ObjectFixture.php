<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;

final class ObjectFixture extends FixtureDefinition
{
    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        $instance = (new \ReflectionClass($this->type()))->newInstanceWithoutConstructor();

        foreach ($this->data() as $key => $value) {
            if ($value instanceof FixtureDefinition && $value->isMethodCall()) {
                $arguments = array_map(function (FixtureDefinition $definition) use ($definitionLocator) {
                    return $definition->instantiate($definitionLocator);
                }, $value->data());
                call_user_func_array([$instance, $key], $arguments);
                continue;
            }

            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $value->instantiate($definitionLocator));
        }

        return $instance;
    }
}
