<?php
namespace Symfony\Component\IETF\Tests\Util;

use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class StringReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleReading()
    {
        $reader = new StringReader('myString!');

        $this->assertFalse($reader->eof());
        $this->assertNull($reader->current());

        $this->assertEquals('m', $reader->read());
        $this->assertFalse($reader->eof());

        $this->assertEquals('y', $reader->read());
        $this->assertEquals('St', $reader->peek(2));
        $this->assertFalse($reader->eof());

        $this->assertEquals('String!', $reader->peek(null));
        $this->assertEquals('String', $reader->read(6));
        $this->assertFalse($reader->eof());

        $this->assertEquals('!', $reader->read());

        $this->assertTrue($reader->eof());
        $this->assertNull($reader->read());
    }

    public function testSimpleMatching()
    {
        $reader = new StringReader('myString!');

        $this->assertEquals(array('my', 'my'), $reader->match('/^([A-Z]{2})/i'));
        $this->assertNull($reader->match('/^([A-Z]{2})/'));
        $this->assertEquals('y', $reader->current());

        $this->assertEquals(array('String'), $reader->peekMatch('/[^!]+/'));
        $this->assertNull($reader->peekMatch('/[\^]/'));
        $this->assertEquals('S', $reader->read());
        $reader->moveCursor(1);

        $this->assertEquals(
            array('ring!'),
            $reader->match('/^.*/')
        );

        $this->assertTrue($reader->eof());
        $this->assertNull($reader->match('/.*/'));
        $this->assertNull($reader->read());
    }

    /**
     * @dataProvider getInvalidConstructData
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorOnlyAcceptsStrings($value)
    {
        return new StringReader($value);
    }

    public function getInvalidConstructData()
    {
        return array(
            array(null),
            array(false),
            array(new \stdClass()),
            array(1)
        );
    }
}
