<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Andx implements PartInterface
{
    /**
     * @var PartInterface[]
     */
    private $parts;

    public function __construct(array $parts)
    {
        $this->parts = $parts;
    }

    public function parse(StringReader $reader)
    {
        $rtn = array();
        foreach ($this->parts as $key => $part) {
            $res = $part->parse($reader);
            if ($res === false) {
                return false;
            }
            $rtn[$key] = $res;
        }
        return $rtn;
    }
}
