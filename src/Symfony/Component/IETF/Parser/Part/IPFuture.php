<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IPFuture implements PartInterface
{
    /**
     * IP future pattern.
     * Used by RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.2.2).
     */
    const PATTERN = '
        (?<ipvfuture>
          v[0-9a-f]+ \. [a-z0-9\-\._\~!\$&\'()*+,;=:]+ # "v" 1*HEXDIG "." 1*( unreserved / sub-delims / ":" )
        ) # a future IP address
    ';

    public function parse(StringReader $reader)
    {
        $matches = $reader->match('~^'.self::PATTERN.'~iux');
        if ($matches === null) {
            return false;
        }
        return $matches['ipvfuture'];
    }
}
