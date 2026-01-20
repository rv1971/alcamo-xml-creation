<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\collection\Collection;
use alcamo\exception\SyntaxError;
use Ds\Set;

class AttributeTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics($name, $content, $expectedString): void
    {
        $attr = new Attribute($name, $content);

        $this->assertSame($name, $attr->getName());

        $this->assertSame($content, $attr->getContent());

        $this->assertEquals($expectedString, (string)$attr);
    }

    public function basicsProvider(): array
    {
        $content = 'bar="baz"';

        return [
            'text-content' => [
                'foo',
                'At vero "eos" et <accusam> et justo duo dolores et ea rebum.',
                'foo="At vero &quot;eos&quot; et &lt;accusam&gt; et justo duo dolores et ea rebum."'
            ],

            'array-content' => [
                'bar',
                [ '123', '<4567>', "'quux'" ],
                "bar=\"123 &lt;4567&gt; 'quux'\""
            ],

            'empty-array-content' => [
                'baz',
                [],
                ''
            ],

            'set-content' => [
                'class',
                new Set([ 'bold', 'green' ]),
                'class="bold green"'
            ],

            'empty-set-content' => [
                'style',
                new Set(),
                ''
            ],

            'empty-object-content' => [
                'baz',
                new Collection(),
                ''
            ],

            'empty-string' => [
                'baz',
                '',
                'baz=""'
            ],

            'null-content' => [
                'baz',
                null,
                ''
            ],

            'iterable-object-content' => [
                'BAZ:QUX',
                new Collection([ 'FOO', '"Foo', 'foo' ]),
                'BAZ:QUX="FOO &quot;Foo foo"'
            ]
        ];
    }

    public function testException(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            "Syntax error in \"-bar\"; not a valid XML attribute name"
        );

        new Attribute('-bar', 'baz');
    }
}
