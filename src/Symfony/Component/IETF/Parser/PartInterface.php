<?php
namespace Symfony\Component\IETF\Parser;

use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface PartInterface {
    public function parse(StringReader $reader);
}
