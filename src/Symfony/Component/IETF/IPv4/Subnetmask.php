<?php
namespace Symfony\Component\IETF\IPv4;

/**
 * https://erikberg.com/notes/networks.html#reserved
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
final class Subnetmask
{
    const MIN_LONG = 0;
    const MAX_LONG = 4294967295;

    private $long;

    private function __construct($decimal)
    {
        if ($decimal < self::MIN_LONG || $decimal > self::MAX_LONG ) {
            throw new \InvalidArgumentException('Out of range');
        }
    }

    public function getLong()
    {
        return $this->long;
    }

    public function getDecimal()
    {
        return long2ip($this->long);
    }

    public function getBinary()
    {
        return decbin($this->long);
    }

    public function getHex()
    {
        return dechex($this->long);
    }

    public function getCIDR()
    {
        $one = rtrim(decbin($this->long), '0');
        if (strpos($one, '0') !== false) {
            // Can't convert to CIDR
            return null;
        }

        // /8 == first 8 bits to 1 == CIDR prefix
        return strlen($one);
    }

    public function getInverted()
    {
        return new self($this->long ^ self::MAX_LONG);
    }

    public function equals(Subnetmask $mask)
    {
        return $this->long === $mask->long;
    }

    /**
     * Binary notation
     */
    public static function createFromBinaryNotation($binary)
    {
        if (strlen($binary) !== 32) {
            throw new \InvalidArgumentException();
        }

        return new self(bindec($binary));
    }

    /**
     * CIDR prefix
     */
    public static function createFromCidrNotation($cidr)
    {
        if ($cidr < 0 || $cidr > 32) {
            throw new \InvalidArgumentException();
        }

        return self::createFromBinaryNotation(str_repeat('1', 8) . str_repeat('0', 32 - 8));
    }

    /**
     * Decimal notation.
     */
    public static function createFromDecimalNotation($ip)
    {
        $long = ip2long($ip);
        if ($long === false) {
            throw new \InvalidArgumentException();
        }

        return new self($long);
    }

    /**
     * Hexadecimal notation.
     */
    public static function createFromHexadecimalNotation($value)
    {
        if(!preg_match('/^[0-9a-f]$', $value)) {
            throw new \InvalidArgumentException();
        }

        return new self(hexdec($value));
    }
}
