<?php

namespace alcamo\xml_creation;

/**
 * @namespace alcamo::xml_creation
 *
 * @brief Simple classes to create XML code without need for a factory
 */

/**
 * @brief XML node that can be serialized to XML text
 *
 * @date Last reviewed 2026-01-20
 */
abstract class AbstractNode implements NodeInterface
{
    protected $content_;

    public function __construct($content = null)
    {
        $this->content_ = $content;
    }

    public function getContent()
    {
        return $this->content_;
    }

    abstract public function __toString(): string;
}
