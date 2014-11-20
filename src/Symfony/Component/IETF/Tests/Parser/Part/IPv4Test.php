<?php
namespace Symfony\Component\IETF\Tests\Parser\Part;

use Symfony\Component\IETF\Parser\Part\IPv4;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IPv4Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IPv4
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new IPv4();
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
            array('10.0.0.1'),
            array('172.16.0.1'),
            array('192.168.0.1'),
            array('0.0.0.0'),
            array('216.17.184.1'),

            // Address + extra
            array('216.17.184.1 test', '216.17.184.1'),
            array('172.16.0.1 ::', '172.16.0.1'),
            array('216.17.184.1.' , '216.17.184.1'),
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
            array('www.neely.cx'),
            array('216.17.184.G'),
            array('216.17.184'),
            array('216.17.184.'),
            array('256.17.184.1'),
            array('ABCD:EF01:2345:6789:ABCD:EF01:2345:6789'),
            array('::1'),
            array('::'),
        );
    }
}
