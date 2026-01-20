<?php

namespace alcamo\xml_creation;

/**
 * @brief Raw XML code to be serialized unchanged
 *
 * This will be copied unchanged to the output by
 * alcamo::xml_creation::Nodes::toXmlString().
 *
 * @date Last reviewed 2026-01-20
 */
class Raw extends AbstractNode
{
    public function __toString(): string
    {
        return (string)$this->content_;
    }
}
