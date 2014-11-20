<?php
namespace Symfony\Component\IETF\IPv4;

/**
 * http://tools.ietf.org/html/rfc1878 http://www.oav.net/mirrors/cidr.html
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Netmask {

    /**
     * @param  int $netmask
     * @return boolean
     */
    public static function isValidPrefix($netmask) {
        return $netmask >= 1 && $netmask <= 32;
    }

    public function getBinary();





    public function getPrefix();


}
