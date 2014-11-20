<?php
namespace Symfony\Component\IETF\Parser;

use Symfony\Component\IETF\Util\StringReader;

/**
 * http://tools.ietf.org/html/rfc6068
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MailToParser
{
    /**
     * http://tools.ietf.org/html/rfc6068#section-2
     */
    public function __construct()
    {
        $this->components = array(
            'scheme' => $this->getSchemaPart(),
            'to' => $this->getToPart(),
            'hfields' => $this->getQueryPart(),
            'fragment' => $this->getFragmentPart(),
        );
    }

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
        return new Part\String('mailto:');
    }

    protected function getToPart()
    {

    }
}
