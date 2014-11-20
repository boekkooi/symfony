<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IPv6 implements PartInterface
{
    /**
     * IPv6 pattern
     */
    const PATTERN = '
      (?<ipv6>
        (?:
          (?:
            (?<ipv6h16>[0-9a-f]{1,4}):(?:(?P>ipv6h16):){5}
            (?<ipv6ls32>
              (?:(?P>ipv6h16):(?P>ipv6h16))
              |
              (?<ipv6ipv4>
                (?<ipv6ipv4Dec>25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)
                (?:\.(?P>ipv6ipv4Dec)){3}
              )
            )
          ) # 6( h16 ":" ) ls32
          |
          (?:::(?:(?P>ipv6h16):){5}(?P>ipv6ls32)) # "::" 5( h16 ":" ) ls32
          |
          (?:(?P>ipv6h16)?::(?:(?P>ipv6h16):){4}(?P>ipv6ls32)) # [ h16 ] "::" 4( h16 ":" ) ls32
          |
          (?:(?:(?:(?P>ipv6h16):){1,2}|:):(?:(?P>ipv6h16):){3}(?P>ipv6ls32)) # [ *1( h16 ":" ) h16 ] "::" 3( h16 ":" ) ls32
          |
          (?:(?:(?:(?P>ipv6h16):){1,3}|:):(?:(?P>ipv6h16):){2}(?P>ipv6ls32)) # [ *2( h16 ":" ) h16 ] "::" 2( h16 ":" ) ls32
          |
          (?:(?:(?:(?P>ipv6h16):){1,4}|:):(?P>ipv6h16):(?P>ipv6ls32)) # [ *3( h16 ":" ) h16 ] "::" h16 ":" ls32
          |
          (?:(?:(?:(?P>ipv6h16):){1,5}|:):(?P>ipv6ls32)) # [ *4( h16 ":" ) h16 ] "::" ls32
          |
          (?:(?:(?:(?P>ipv6h16):){1,6}|:):(?P>ipv6h16)) # [ *5( h16 ":" ) h16 ] "::" h16
          |
          (?:(?:(?:(?P>ipv6h16):){1,7}|:):) # [ *6( h16 ":" ) h16 ] "::"
        )
      )
    ';

    public function parse(StringReader $reader)
    {
        $matches = $reader->match('~^'.self::PATTERN.'~iux');
        if ($matches === null) {
            return false;
        }
        return $matches['ipv6'];
    }
}
