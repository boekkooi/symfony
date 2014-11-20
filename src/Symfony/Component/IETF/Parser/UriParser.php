<?php
namespace Symfony\Component\IETF\Parser;

use Symfony\Component\IETF\Util\StringReader;

/**
 * URI/RFC3986 parser (http://tools.ietf.org/html/rfc3986)
 *
 * TODO: add http://tools.ietf.org/html/rfc6874
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class UriParser
{
    /**
     * RFC3986 Scheme pattern (http://tools.ietf.org/html/rfc3986#section-3.1).
     */
    const PATTERN_SCHEME = '
      (?<scheme>[a-z][a-z0-9+-\.]*)
    ';

    /**
     * Userinfo pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.2.1)
     * @remark This pattern is the as for RFC2396 (http://tools.ietf.org/html/rfc2396#section-3.2.2).
     */
    const PATTERN_USERINFO = '
        (?:
          (?<userinfo>
           (?:[a-z0-9\-\._\~!\$&\'()*+,;=:]|%[0-9a-f]{2})*
          )@ # *( unreserved / pct-encoded / sub-delims / ":" )
        )?
    ';

    /**
     * RFC3986 registered name pattern (http://tools.ietf.org/html/rfc3986#section-3.2.2).
     */
    const PATTERN_REGNAME = '
        (?<hostname>
            (?:[a-z0-9\-\._\~!\$&\'()*+,;=]|%[0-9a-f]{2})*
        ) # *( unreserved / pct-encoded / sub-delims )
    ';

    /**
     * Path-abempty pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.3)
     */
    const PATTERN_PATH_ABEMPTY = '
        (?<pathabempty>
          (?:
            /(?<segment>(?:[a-z0-9\-\._\~!\$&\'()*+,;=:@]|%[0-9a-f]{2})*) # *pchar
          )*
        ) # *( "/" segment )
    ';

    /**
     * Path-rootless pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.3)
     */
    const PATTERN_PATH_ROOTLESS = '
        (?<pathrootless>
          (?<pchar>[a-z0-9\-\._\~!\$&\'()*+,;=:@]|%[0-9a-f]{2})+
          (?:/(?P>pchar)*)*
        )
    ';

    /**
     * Path-absolute pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.3)
     */
    const PATTERN_PATH_ABSOLUTE = '
        (?<pathabsolute>
          /
          (?:
            (?<pchar>[a-z0-9\-\._\~!\$&\'()*+,;=:@]|%[0-9a-f]{2})+
            (?:/(?P>pchar)*)*
          )?
        ) # "/" [ segment-nz *( "/" segment ) ]
    ';

    /**
     * Path-empty pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.3)
     */
    const PATTERN_PATH_EMPTY = '
        (?![a-z0-9\-\._\~!\$&\'()*+,;=:@]|%[0-9a-f]{2}) # 0<pchar>
    ';

    /**
     * Port pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.2.3).
     */
    const PATTERN_PORT = '
        (?:
            :(?<port>[0-9]*)
        )?
    ';

    /**
     * Query pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.4)
     */
    const PATTERN_QUERY = '
        (?:
          \?
          (?<query>
            (?:[a-z0-9\-\._\~!\$&\'()*+,;=:@/?]|%[0-9a-f]{2})*
          ) # *( pchar / "/" / "?" )
        )?
    ';

    /**
     * Fragment pattern based on RFC3986 (http://tools.ietf.org/html/rfc3986#section-3.5)
     */
    const PATTERN_FRAGMENT = '
        (?:
          \#
          (?<fragment>
            (?:[a-z0-9\-\._\~!\$&\'()*+,;=:@/?]|%[0-9a-f]{2})*
          ) # *( pchar / "/" / "?" )
        )?
    ';

    public function __construct()
    {
        $this->components = array(
            'scheme' => $this->getSchemaPart(),
            'hier-part' => $this->getHierPart(),
            'query' => $this->getQueryPart(),
            'fragment' => $this->getFragmentPart(),
        );
    }

    public function parse(StringReader $reader)
    {
        $parse = new Part\Andx($this->components);
        $data = $parse->parse($reader);

        var_dump($data);
    }

    /**
     * @return PartInterface
     */
    protected function getSchemaPart()
    {
        return new Part\Andx(array(
            new Part\Regex('~^'.self::PATTERN_SCHEME.'~iux'),
            new Part\String(':'),
        ));
    }

    /**
     * @return PartInterface
     */
    protected function getHierPart()
    {
        return new Part\Orx(array(
            new Part\Andx(array(
                new Part\String('//'),
                'userinfo' => new Part\Regex('~^'.self::PATTERN_USERINFO.'~iux'),
                'host' => $this->getHostPart(),
                'port' => new Part\Regex('~^'.self::PATTERN_PORT.'~iux'),
                'path-abempty' => new Part\Regex('~^'.self::PATTERN_PATH_ABEMPTY.'~iux'),
            )),
            new Part\Regex('~^'.self::PATTERN_PATH_ABSOLUTE.'~iux'),
            new Part\Regex('~^'.self::PATTERN_PATH_ROOTLESS.'~iux'),
            new Part\Regex('~^'.self::PATTERN_PATH_EMPTY.'~iux', false),
        ));
    }

    /**
     * @return PartInterface
     */
    protected function getHostPart()
    {
        return new Part\Orx(array(
            new Part\Andx(array(
                    new Part\String('['),
                    new Part\Orx(array(
                            new Part\IPv4(),
                            new Part\IPv6(),
                            new Part\IPFuture(),
                        )),
                    new Part\String(']'),
                )),
            new Part\Regex('~^'.self::PATTERN_REGNAME.'~iux'),
        ));
    }

    /**
     * @return PartInterface
     */
    protected function getQueryPart()
    {
        return new Part\Regex('~^'.self::PATTERN_QUERY.'~iux');
    }

    /**
     * @return PartInterface
     */
    protected function getFragmentPart()
    {
        return new Part\Regex('~^'.self::PATTERN_FRAGMENT.'~iux');
    }
}
