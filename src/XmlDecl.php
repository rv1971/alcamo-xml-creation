<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML declaration that can be serialized to XML text
 *
 * @date Last reviewed 2026-01-20
 */
class XmlDecl implements NodeInterface
{
    protected $version_;    ///< string
    protected $encoding_;   ///< string
    protected $standalone_; ///< bool

    /**
     * @param $version XML version, defaults to 1.0
     *
     * @param $encoding Encoding, defaults to UTF-8
     *
     * @param $standalone Standalone document declaration, defaults to
     * `false`.
     */
    public function __construct(
        ?string $version = null,
        ?string $encoding = null,
        ?bool $standalone = null
    ) {
        if (
            isset($version) && !preg_match(Syntax::VERSION_NUM_REGEXP, $version)
        ) {
            /** @throw alcamo::exception::SyntaxError if $version is not a
             *  valid version. */
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $version,
                    'extraMessage' => 'not a valid XML version'
                ]
            );
        }

        $this->version_ = $version ?? '1.0';

        if (
            isset($encoding) && !preg_match(Syntax::ENC_NAME_REGEXP, $encoding)
        ) {
            /** @throw alcamo::exception::SyntaxError if $encoding is not a
             *  valid encoding. */
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $encoding,
                    'extraMessage' => 'not a valid XML encoding'
                ]
            );
        }

        $this->encoding_ = $encoding ?? 'UTF-8';

        $this->standalone_ = $standalone ?? false;
    }

    /**
     * @copybrief alcamo::xml_creation::NodeInterface::getContent()
     *
     * Always returns `null`.
     */
    public function getContent()
    {
        return null;
    }

    public function getVersion(): string
    {
        return $this->version_;
    }

    public function getEncoding(): string
    {
        return $this->encoding_;
    }

    public function getStandalone(): bool
    {
        return $this->standalone_;
    }

    public function __toString(): string
    {
        $result =
            "<?xml version=\"$this->version_\" encoding=\"$this->encoding_\"";

        if ($this->standalone_) {
            $result .= ' standalone="yes"';
        }

        return "$result?>";
    }
}
