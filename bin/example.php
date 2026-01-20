#!/usr/bin/env php
<?php

use alcamo\xml_creation\{
    Comment,
    DoctypeDecl,
    Element,
    Nodes,
    ProcessingInstruction,
    Raw,
    XmlDecl
};

include $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

Nodes::setFormatOutput(true);

$xml = new Nodes(
    new XmlDecl(),
    new DoctypeDecl(
        'foo',
        'SYSTEM "foo.dtd"',
        '<!ATTLIST bar id ID #IMPLIED>'
    ),
    new ProcessingInstruction(
        'xml-stylesheet',
        'href="xsl/foo.xsl" type="text/xsl"'
    ),
    new Element(
        'foo',
        [ 'xml:lang' => 'is' ],
        [
            new Comment('Lorem <ipsum> dolor.'),
            new Element('bar', [ 'id' => 'my-bar' ]),
            new Raw('<baz>consetetur sadipscing elitr</baz>')
        ]
    )
);

echo $xml;

echo PHP_EOL;

Nodes::setFormatOutput(false);

echo $xml;
