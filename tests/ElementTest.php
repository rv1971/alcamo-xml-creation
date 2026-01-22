<?php

namespace alcamo\xml_creation;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\collection\Collection;
use alcamo\exception\SyntaxError;

class ElementTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $tagName,
        $attrs,
        $content,
        $expectedString,
        $expectedOpeningTag,
        $expectedClosingTag
    ): void {
        $element = new Element($tagName, $attrs, $content);

        $this->assertSame($tagName, $element->getTagName());

        $this->assertSame(
            $attrs instanceof Map ? $attrs->toArray() : (array)$attrs,
            $element->getAttrs()
        );

        $this->assertSame($content, $element->getContent());

        $this->assertSame($expectedString, (string)$element);

        $this->assertSame($expectedOpeningTag, $element->createOpeningTag());

        $this->assertSame($expectedClosingTag, $element->createClosingTag());
    }

    public function basicsProvider(): array
    {
        return [
            'empty-tag' => [
                'foo', null, null, '<foo/>', '<foo>', '</foo>'
            ],
            'empty-tag-with-attrs' => [
                'bar',
                [ 'baz' => '<<<qux>>>', 'QUUX' => [ 1, 2, 3 ] ],
                null,
                '<bar baz="&lt;&lt;&lt;qux&gt;&gt;&gt;" QUUX="1 2 3"/>',
                '<bar baz="&lt;&lt;&lt;qux&gt;&gt;&gt;" QUUX="1 2 3">',
                '</bar>'
            ],
            'tag-with-text-content' => [
                'baz',
                null,
                'Stet "clita" \'kasd\' gubergren & no <sea> takimata sanctus est Lorem ipsum dolor sit amet.',
                '<baz>Stet "clita" \'kasd\' gubergren &amp; no &lt;sea&gt; '
                . 'takimata sanctus est Lorem ipsum dolor sit amet.</baz>',
                '<baz>',
                '</baz>',
            ],
            'tag-with-text-content-and-attrs' => [
                'qux',
                [ 'xml:id' => 'QUX', 'xml:lang' => 'oc' ],
                'Coordinacion de totes los projèctes',
                '<qux xml:id="QUX" xml:lang="oc">Coordinacion de totes los projèctes</qux>',
                '<qux xml:id="QUX" xml:lang="oc">',
                '</qux>'

            ],
            'tag-with-array-content-and-attrs' => [
                'ns42:quux',
                [ 'rdf:ID' => 'element-42' ],
                [
                    'Lorem ',
                    new Element('xh:b', null, 'ipsum'),
                    ' dolor sit ',
                    new Raw('<i>amet</i>')
                ],
                '<ns42:quux rdf:ID="element-42">Lorem <xh:b>ipsum</xh:b> dolor sit <i>amet</i></ns42:quux>',
                '<ns42:quux rdf:ID="element-42">',
                '</ns42:quux>'

            ],
            'tag-with-complex-content-and-attr-object' => [
                'body',
                new Map([
                    'xmlns' => 'http://www.w3.org/1999/xhtml',
                    'class' => 'overview'
                ]),
                new Element(
                    'div',
                    [ 'class' => 'main' ],
                    new Collection([
                        'Stet clita kasd gubergren, ',
                        new Element('i', null, 'no sea takimata'),
                        '.'
                    ])
                ),
                '<body xmlns="http://www.w3.org/1999/xhtml" class="overview">'
                . '<div class="main">Stet clita kasd gubergren, <i>no sea takimata</i>.</div></body>',
                '<body xmlns="http://www.w3.org/1999/xhtml" class="overview">',
                '</body>'
            ]
        ];
    }

    public function testTagNameException(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in ".qux"; not a valid XML tag name'
        );

        new Element('.qux');
    }

    public function testAttrNameException(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in 424242; not a valid XML attribute name'
        );

        try {
            new Element('quux', [ '424242' => 'bar' ]);
        } catch (SyntaxError $e) {
            $this->assertSame('quux', $e->getMessageContext()['tagName']);

            throw $e;
        }
    }
}
