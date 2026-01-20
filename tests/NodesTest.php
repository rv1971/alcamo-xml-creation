<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

/* toXmlString() is implicitely tested in ElementTest.php */

class NodesTest extends TestCase
{
    public function testBasics(): void
    {
        $data1 = [ 'a', 'B' ];
        $data2 = [ 'CC', 'ddd' ];
        $data3 = [ 'eeee', 'fffff' ];

        $allData = array_merge($data1, $data2, $data3);

        $nodes = new Nodes([ $data1, [ $data2, [ $data3 ] ] ]);

        $this->assertEquals($allData, $nodes->getNodes());

        $node = new Element('foo');

        $allData[] = $node;

        $nodes->append($node);

        $this->assertEquals($allData, $nodes->getNodes());
    }

    public function testFormatOutput(): void
    {
        $nodes = new Nodes(
            new Element('foo', null, 'Lorem ipsum'),
            new Element('bar', null, new Element('baz')),
            new Element(
                'qux',
                null,
                [
                    new Element('quux'),
                    new Element('corge')
                ]
            )
        );

        Nodes::setFormatOutput(true);

        $this->assertSame(
            '<foo>Lorem ipsum</foo>' . PHP_EOL
                . '<bar>' . PHP_EOL
                . '<baz/>' . PHP_EOL
                . '</bar>' . PHP_EOL
                . '<qux>' . PHP_EOL
                . '<quux/>' . PHP_EOL
                . '<corge/>' . PHP_EOL
                . '</qux>' . PHP_EOL,
            (string)$nodes
        );
    }
}
