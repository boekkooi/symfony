<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IPv4 implements PartInterface
{
    /**
     * IPv4 pattern
     */
    const PATTERN = '
        (?<ipv4>
            (?<ipv4Dec>25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)
            (?:\.(?P>ipv4Dec)){3}
        ) # a IPv4 address
    ';

    public function parse(StringReader $reader)
    {
        $matches = $reader->match('~^'.self::PATTERN.'~iux');
        if ($matches === null) {
            return false;
        }
        return $matches['ipv4'];
    }
}
