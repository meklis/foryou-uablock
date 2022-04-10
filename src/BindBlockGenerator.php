<?php

namespace Meklis\Blocks;

class BindBlockGenerator
{
    protected $addresses = [];
    protected $header = '
;
; BIND data file for blocked domain resources
;
$TTL    604800
@       IN      SOA        LOCALHOST. LOCALHOST.root (
                              2         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL

                NS  LOCALHOST.
    
';

    protected Configuration $configuration;

    function __construct(Configuration $conf)
    {
        $this->configuration = $conf;
    }
    function setDomains($addresses = []) {
        $this->addresses = $addresses;
        return clone $this;
    }

    function addDomains($addresses = [])
    {
        $this->addresses = array_merge($this->addresses, $addresses);
        return clone $this;
    }

    protected function getRulesAsArray()
    {
        $rules = [];
        foreach ($this->addresses as  $domain) {
            $domain = Helper::idnTransform($domain);
            $rules[] = "{$domain}       IN      A       {$this->configuration->getReplacedAddress()}";
        }
        return $rules;
    }

    function getConfiguration()
    {
        return $this->header . join("\n", $this->getRulesAsArray()) . "\n";
    }
}