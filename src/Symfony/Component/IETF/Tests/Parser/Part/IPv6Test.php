<?php
namespace Symfony\Component\IETF\Tests\Parser\Part;

use Symfony\Component\IETF\Parser\Part\IPv6;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IPv6Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IPv6
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new IPv6();
    }

    /**
     * @dataProvider getValidAddress
     */
    public function testValidAddress($address, $expected = null)
    {
        $this->assertEquals(
            $expected === null ? $address : $expected,
            $this->parser->parse(new StringReader($address))
        );
    }

    public function getValidAddress()
    {
        return array(
            // Address only
            array('ABCD:EF01:2345:6789:ABCD:EF01:2345:6789'),
            array('2001:DB8:0:0:8:800:200C:417A'),
            array('FF01:0:0:0:0:0:0:101'),
            array('2001:DB8::8:800:200C:417A'),
            array('FF01::101'),
            array('::1'),
            array('::'),
            array('0:0:0:0:0:0:13.1.68.3'),
            array('0:0:0:0:0:FFFF:129.144.52.38'),
            array('::13.1.68.3'),
            array('::FFFF:129.144.52.38'),
            array('2001:0DB8:0:CD30:123:4567:89AB:CDEF'),

            // Address + extra
            array('::1 test', '::1'),
            array(':: ::', '::'),
        );
    }

    /**
     * @dataProvider getInvalidAddress
     */
    public function testInvalidAddress($address)
    {
        $this->assertFalse(
            $this->parser->parse(new StringReader($address))
        );
    }

    public function getInvalidAddress()
    {
        return array(
            array('2067:FA88'),
            array('216.17.184.1'),
            array('www.neely.cx')
        );
    }
}
