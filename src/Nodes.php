<?php

namespace alcamo\xml_creation;

use alcamo\collection\Collection;

/**
 * @brief Array of XML nodes that can be serialized to XML text
 *
 * @attention Provides an optional formatting out output, but a very basic
 * one:
 * - No indenttation takes place.
 * - If the only content of an element is neither an implenentation of
 * alcamo::xml_creation::NodeInterface nor an iterable, it is output without
 * surrounding space. Otherwise, line breaks are inserted between content
 * items, which may introduce unwanted whitespace. The output formatting is
 * mainly intended to facilitate debugging.
 *
 * @date Last reviewed 2026-01-20
 */
class Nodes extends Collection
{
    private static $formatOutput_;

    public static function getFormatOutput(): bool
    {
        return self::$formatOutput_;
    }

    public static function setFormatOutput(bool $formatOutput): void
    {
        self::$formatOutput_ = $formatOutput;
    }

    /**
     * @brief Return serialized XML text
     *
     * @param $nesting Must be 2 when called from
     * alcamo::xml_creation::Element::__toString() and 1 when called from
     * toXmlString().
     *
     * - Invoke __toString() on NodeInterface objects.
     * - Handle iterables recursively by calling toXmlString() on each item.
     * - Encode any other data with
     *   [htmlspecialchars()](https://www.php.net/manual/en/function.htmlspecialchars).
     */
    public static function toXmlString($data, ?int $nesting = null): string
    {
        switch (true) {
            case $data instanceof NodeInterface:
                return $nesting && self::$formatOutput_
                    ? ($nesting == 2 ? PHP_EOL : '') . $data . PHP_EOL
                    : $data;

            case is_iterable($data):
                $output = $nesting && self::$formatOutput_ ? PHP_EOL : '';

                foreach ($data as $item) {
                    $output .= static::toXmlString($item, 1);
                }

                return $output;

            default:
                return htmlspecialchars($data, ENT_NOQUOTES);
        }
    }

    public function __construct(...$data)
    {
        $this->append($data);
    }

    public function getNodes(): array
    {
        return $this->data_;
    }

    /// Serialized XML text
    public function __toString(): string
    {
        return static::toXmlString($this->data_);
    }

    /// Build a flat array of nodes by flattening iterable items
    public function append($data): void
    {
        switch (true) {
            /* This must be tested before is_iterable because a NodeInterface
             * may be iterable. */
            case $data instanceof NodeInterface:
                $this->data_[] = $data;
                return;

            case is_iterable($data):
                foreach ($data as $item) {
                    $this->append($item);
                }

                return;

            default:
                $this->data_[] = $data;
        }
    }
}
