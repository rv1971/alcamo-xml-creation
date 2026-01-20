<?php

namespace alcamo\xml_creation;

use alcamo\collection\Collection;

/**
 * @brief Array of XML nodes that can be serialized to XML text
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
     * - Invoke __toString() on NodeInterface objects.
     * - Handle iterables recursively by calling toXmlString() on each item.
     * - Encode any other data with
     *   [htmlspecialchars()](https://www.php.net/manual/en/function.htmlspecialchars).
     */
    public static function toXmlString($data, ?bool $isNested = null): string
    {
        switch (true) {
            case $data instanceof NodeInterface:
                return $isNested && self::$formatOutput_
                    ? $data . PHP_EOL
                    : $data;

            case is_iterable($data):
                $output = $isNested && self::$formatOutput_ ? PHP_EOL : '';

                foreach ($data as $item) {
                    $output .= static::toXmlString($item, true);
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
            /* This must be tested before is_iterable because NodeInterface
             * exteds is_iterable. */
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
