<?php
namespace Symfony\Component\IETF\Tests\Parser;

use Symfony\Component\IETF\Parser\UriParser;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class UriParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UriParser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new UriParser();
    }

    /**
     * @dataProvider getValidUris
     */
    public function testParsing($uri)
    {
        $this->parser->parse(
            new StringReader($uri)
        );
    }

    public function getValidUris() {
        return array(
            array('ftp://ftp.is.co.za/rfc/rfc1808.txt'),
            array('http://www.ietf.org/rfc/rfc2396.txt'),
            array('ldap://[2001:db8::7]/c=GB?objectClass?one'),
            array('mailto:John.Doe@example.com'),
            array('news:comp.infosystems.www.servers.unix'),
            array('tel:+1-816-555-1212'),
            array('telnet://192.0.2.16:80/'),
            array('urn:oasis:names:specification:docbook:dtd:xml:4.1.2'),
            array('foo://example.com:8042/over/there?name=ferret#nose')
        );
    }
}
