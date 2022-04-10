<?php

namespace Meklis\Blocks;

class Helper
{

    public static function idnTransform($domain) {
        if(preg_match('/[а-яА-Я]/', $domain)) {
            return idn_to_ascii($domain);
        }
        return $domain;
    }
    public static function resolv4($domain) {
        $ips = [];
        exec("nslookup $domain | grep -E 'Address: ([0-9{1,3}].*)' | awk '{print $2}'", $output);
        foreach ($output as $ip) {
            if (preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/i", $ip) && $ip != "127.0.0.1") {
                $ips[$ip]=$domain;
            }
        }
        return $ips;
    }
}