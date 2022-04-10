<?php

namespace Meklis\Blocks;

class Resolver
{
    protected $resolvList = [];

    function __construct()
    {
    }

    function setList($list = [])
    {
        $this->resolvList = $list;
        return $this;
    }

    function resolv($domain)
    {
        $domain = Helper::idnTransform($domain);
        return Helper::resolv4($domain);
    }

    function resolvListAsKeys()
    {
        $ips = [];
        foreach ($this->resolvList as $domain) {
            foreach ($this->resolv($domain) as $ip=>$_) {
                $ips[$ip] = $domain;
            }
        }
        return $ips;
    }
}