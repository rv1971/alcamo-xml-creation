<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

class RawTest extends TestCase
{
    public function testBasics(): void
    {
        $text =
        '<strong>At vero eos et accusam</strong> et justo duo dolores et ea rebum.';

        $raw = new Raw($text);

        $this->assertSame($text, $raw->getContent());

        $this->assertEquals($text, (string)$raw);
    }
}
