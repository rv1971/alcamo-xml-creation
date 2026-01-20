<?php

namespace alcamo\xml_creation;

use Ds\Set;
use alcamo\collection\{
  CountableTrait,
  DecoratorTrait,
  IteratorAggregateTrait,
  ReadArrayAccessTrait,
  WriteArrayAccessTrait
};

/**
 * @brief Set of space-separated tokens similar to DOMTokenList in JavaScript
 *
 * @date Last reviewed 2026-01-20
 */
class TokenList implements \Countable, \IteratorAggregate, \ArrayAccess
{
    use CountableTrait;
    use DecoratorTrait;
    use IteratorAggregateTrait;
    use ReadArrayAccessTrait;
    use WriteArrayAccessTrait;

    protected $data_; ///< Set

    /**
     * @param iterable|string $tokens If not iterable, $tokens is converted to
     * a string and splitted at whitespace.
     */
    public function __construct($tokens = null)
    {
        if (!isset($tokens)) {
            $this->data_ = new Set();
            return;
        }

        $this->data_ = new Set(
            is_iterable($tokens)
                ? $tokens
                : preg_split('/\s+/', $tokens)
        );
    }

    /// Serialize to space-separated list
    public function __toString(): string
    {
        return $this->join(' ');
    }
}
