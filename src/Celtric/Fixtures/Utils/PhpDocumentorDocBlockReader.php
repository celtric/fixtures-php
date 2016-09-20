<?php

namespace Celtric\Fixtures\Utils;

use phpDocumentor\Reflection\DocBlock;

final class PhpDocumentorDocBlockReader implements DocBlockReader
{
    /**
     * @inheritDoc
     */
    public function getPropertyType($className, $propertyName)
    {
        $comment = (new \ReflectionProperty($className, $propertyName))->getDocComment();

        if (empty($comment)) {
            return null;
        }

        $docBlock = new DocBlock($comment);
        $tags = $docBlock->getTagsByName("var");

        if (empty($tags)) {
            return null;
        }

        return $tags[0]->getContent();
    }
}
