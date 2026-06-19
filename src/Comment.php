<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/**
 * @brief XML comment that can be serialized to XML text
 *
 * @sa [XML comments](https://www.w3.org/TR/xml/#sec-comments)
 *
 * @date Last reviewed 2026-01-20
 */
class Comment extends AbstractNode
{
    public function __construct(string $content)
    {
        if (strpos($content, '--') !== false) {
            /** @throw alcamo::exception::SyntaxError if $content contains
             *  double hyphen. */
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $content,
                    'atOffset' => strpos($content, '--'),
                    'extraMessage' => 'double-hyphen in XML comment'
                ]
            );
        }

        parent::__construct($content);
    }

    public function __toString(): string
    {
        return '<!--' .
            htmlspecialchars(
                $this->content_,
                ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML401
            )
            . '-->';
    }
}
