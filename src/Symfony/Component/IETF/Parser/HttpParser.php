<?php
namespace Symfony\Component\IETF\Parser;

use Symfony\Component\IETF\Util\StringReader;

/**
 * http://tools.ietf.org/html/rfc7230#section-2.7.1
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class HttpParser extends UriParser
{
    public function parse(StringReader $reader)
    {
        $parse = new Part\Andx($this->components);
        $parse->parse($reader);

        var_dump($parse);die;
    }

    /**
     * @return PartInterface
     */
    protected function getSchemaPart()
    {
        return new Part\Regex('~^https?:~i');
    }

    /**
     * @return PartInterface
     */
    protected function getHierPart()
    {
        return new Part\Andx(array(
            new Part\String('//'),
            'userinfo' => new Part\Regex('~^'.self::PATTERN_USERINFO.'~iux'),
            'host' => $this->getHostPart(),
            'port' => new Part\Regex('~^'.self::PATTERN_PORT.'~iux'),
            'path-abempty' => new Part\Regex('~^'.self::PATTERN_PATH_ABEMPTY.'~iux'),
        ));
    }
}
