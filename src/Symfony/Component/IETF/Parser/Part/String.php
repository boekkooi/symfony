<?php
namespace Symfony\Component\IETF\Parser\Part;

use Symfony\Component\IETF\Parser\PartInterface;
use Symfony\Component\IETF\Util\StringReader;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class String implements PartInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $length;

    public function __construct($string)
    {
        $this->string = (string)$string;
        $this->length = strlen($this->string);
    }

    public function parse(StringReader $reader)
    {
        if ($reader->peek($this->length) === $this->string) {
            $reader->moveCursor($this->length);
            return $this->string;
        }
        return false;
    }
}
