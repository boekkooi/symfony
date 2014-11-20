<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Orx implements PartInterface
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
        $valid = false;
        foreach ($this->parts as $part) {
            $res = $part->parse($reader);
            if ($res === null) {
                $valid = true;
                continue;
            }
            if ($res !== false) {
                return $res;
            }
        }
        return $valid ? null : false;
    }
}
