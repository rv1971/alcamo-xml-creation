<?php

namespace alcamo\xml_creation;

/**
 * @brief XML node that can be serialized to XML text
 *
 * @date Last reviewed 2026-01-20
 */
interface NodeInterface
{
    /// Node content as given to the constructor, may not be a string
    public function getContent();

    /// Serialized XML text
    public function __toString(): string;
}
