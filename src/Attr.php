<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;
use Ds\Set;

/**
 * @brief XML attribute that can be serialized to XML text
 *
 * @sa [XML logical structures](https://www.w3.org/TR/xml/#sec-logical-struct)
 *
 * @date Last reviewed 2026-01-20
 */
class Attr extends AbstractNode
{
    protected $name_; ///< string

    public function __construct(string $name, $content)
    {
        if (!preg_match(Syntax::NAME_REGEXP, $name)) {
            /** @throw alcamo::exception::SyntaxError if $name is not a valid
             *  name. */
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $name,
                    'extraMessage' => 'not a valid XML attribute name'
                ]
            );
        }

        $this->name_ = $name;

        parent::__construct($content);
    }

    public function getName(): string
    {
        return $this->name_;
    }

    /**
     * @copybrief alcamo::xml_creation::NodeInterface::__toString()
     *
     * @return
     * - Empty string if the content is `null`, an empty array or an empty
     * iterable, thus omitting attributes which are empty in this sense.
     * - Space-separated list if the content is a nonempty array or iterable,
     * applying any necessary escaping.
     * - Content converted to string in any other case, applying any necessary
     * escaping.
     */
    public function __toString(): string
    {
        switch (true) {
            case !isset($this->content_):
                return '';

            case is_array($this->content_):
                if (!$this->content_) {
                    return '';
                }

                $valueString = implode(' ', $this->content_);
                break;

            case $this->content_ instanceof Set:
                if ($this->content_->isEmpty()) {
                    return '';
                }

                $valueString = $this->content_->join(' ');
                break;

            case is_iterable($this->content_):
                $valueString = '';

                foreach ($this->content_ as $item) {
                    if ($valueString != '') {
                        $valueString .= " $item";
                    } else {
                        $valueString = $item;
                    }
                }

                if ($valueString == '') {
                    return '';
                }

                break;

            default:
                $valueString = (string)$this->content_;
        }

        return "{$this->name_}=\"" .
            htmlspecialchars(
                $valueString,
                ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML401
            )
            . '"';
    }
}
