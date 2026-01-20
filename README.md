# Usage example

~~~
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
            new Comment(' Lorem ipsum dolor. '),
            new Element(
                'bar',
                [ 'id' => 'my-bar' ],
                'sed diam <nonumy> eirmod tempor'
            ),
            new Raw('<baz>consetetur sadipscing elitr</baz>')
        ]
    )
);

echo $xml . PHP_EOL;

Nodes::setFormatOutput(false);

echo $xml . PHP_EOL;
~~~

This example is contained in this package as a file in the `bin`
directory. It will output

~~~
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo SYSTEM "foo.dtd" [ <!ATTLIST bar id ID #IMPLIED> ]>
<?xml-stylesheet href="xsl/foo.xsl" type="text/xsl"?>
<foo xml:lang="is">
<!-- Lorem ipsum &amp; dolor. -->
<bar id="my-bar">sed diam &lt;nonumy&gt; eirmod tempor</bar>
<baz>consetetur sadipscing elitr</baz>
</foo>

<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE foo SYSTEM "foo.dtd" [ <!ATTLIST bar id ID #IMPLIED> ]><?xml-stylesheet href="xsl/foo.xsl" type="text/xsl"?><foo xml:lang="is"><!-- Lorem ipsum &amp; dolor. --><bar id="my-bar">sed diam &lt;nonumy&gt; eirmod tempor</bar><baz>consetetur sadipscing elitr</baz></foo>
~~~

See the doxygen documentation for details.
