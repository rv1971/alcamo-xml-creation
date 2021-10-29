<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML attribute that can be serialized to XML text
 *
 * @sa [XML logical structures](https://www.w3.org/TR/xml/#sec-logical-struct)
 *
 * @date Last reviewed 2021-06-15
 */
class Attribute extends AbstractNode
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

    /// @copydoc NodeInterface::__toString()
    public function __toString(): string
    {
        /** Return empty string if attribute value is `null`, thus omitting
         *  attributes with value `null`. */
        if (!isset($this->content_)) {
            return '';
        } elseif (is_array($this->content_)) {
            /**
             * If the content is an array or iterable, serialize it to a
             * space-separated list. Return empty string for empty attributes
             * or iterables.
             */
            if ($this->content_) {
                $valueString = implode(' ', $this->content_);
            } else {
                return '';
            }
        } elseif (is_iterable($this->content_)) {
            $valueString = '';

            foreach ($this->content_ as $item) {
                if ($valueString) {
                    $valueString .= " $item";
                } else {
                    $valueString = $item;
                }
            }

            if ($valueString == '') {
                return '';
            }
        } else {
            $valueString = (string)$this->content_;
        }

        return "{$this->name_}=\"" . htmlspecialchars($valueString) . '"';
    }
}
