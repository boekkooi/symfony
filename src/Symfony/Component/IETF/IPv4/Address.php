<?php
namespace Symfony\Component\IETF\IPv4;

/**
 * http://www.iana.org/assignments/ipv4-address-space/ipv4-address-space.xhtml
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Address
{
    const PATTERN = '
        (?<ipv4>
            (?<ipv4Dec>25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)
            (?:\.(?P>ipv4Dec)){3}
        )
    ';

    protected $address;

    public function __construct($address) {
        if (!self::isValid($address)) {
            throw new \UnexpectedValueException('"'.$address.'" is no valid IPv4 address.');
        }

        $this->address = $address;
    }

    /**
     * @param  string $addr
     * @return boolean
     */
    public static function isValid($address) {
        return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Get IP binary representation
     *
     * @return string
     */
    public function getBinary()
    {
        return sprintf("%032b",ip2long($this->address));
    }

    /**
     * Get IP Hex representation
     *
     * @return string
     */
    public function getHex()
    {
        $chunks = $this->getChunks();

        $parts = array();
        for($i = 0; $i < 4; $i++) {
            $parts[$i] = str_pad(dechex($chunks[$i]), 2, '0', STR_PAD_LEFT);
        }
        return implode('', $chunks);
    }

    /**
     * Get IP-specific chunks ([127,0,0,1])
     *
     * @return array
     */
    public function getChunks() {
        return explode('.', $this->getExpanded());
    }

    /**
     * Get IP Class
     */
    public function getClass()
    {
        $chunks = $this->getChunks();
        $binary = sprintf("%08b", array_shift($chunks));
        if ($binary[0] === '0') {
            return 'A';
        }
        if ($binary[1] === '0') {
            return 'B';
        }
        if ($binary[2] === '0') {
            return 'C';
        }
        if ($binary[3] === '0') {
            return 'D';
        }
        return 'E';
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->address;
    }











    /**
     * @return self
     */
    public static function getLoopback() {
        return new self('127.0.0.1');
    }

    /**
     * get fully expanded address
     *
     * @return string
     */
    public function getExpanded() {
        return $this->address;
    }

    /**
     * get compact address representation
     *
     * @return string
     */
    public function getCompact() {
        return $this->getExpanded();
    }

    /**
     * returns the compact representation
     *
     * @return string
     */
    public function __toString() {
        return $this->getCompact();
    }

    /**
     * check whether the IP points to the loopback (localhost) device
     *
     * @return boolean
     */
    public function isLoopback() {
        return $this->matches(new Subnet('127.0.0.0/8'));
    }

    /**
     * check whether the IP is inside a private network
     *
     * @return boolean
     */
    public function isPrivate() {
        return
            $this->matches(new Subnet('10.0.0.0/8')) ||
            $this->matches(new Subnet('172.16.0.0/12')) ||
            $this->matches(new Subnet('192.168.0.0/16'))
            ;
    }

    /**
     * check whether the IP is a multicast address
     */
    public function isMulticast() {
        return $this->matches(new Subnet('224.0.0.0/4'));
    }

    /**
     * check whether the IP is a link-local address
     *
     * @return boolean
     */
    public function isLinkLocal() {
        return $this->matches(new Subnet('169.254.1.0/24'));
    }

    /**
     * check whether the address matches a given pattern/range
     *
     * @param  ExpressionInterface $expression
     * @return boolean
     */
    public function matches(ExpressionInterface $expression) {
        return $expression->matches($this);
    }
}
