<?php

namespace Meklis\Blocks;

class MikrotikRulesGenerator
{
    protected $addresses = [];

    protected Configuration $configuration;

    function __construct(Configuration $conf)
    {
        $this->configuration = $conf;
    }
    function setAddresses($addresses = []) {
        $this->addresses = $addresses;

        return $this;
    }

    function addAddresses($addresses = [])
    {
        foreach ($addresses as $ip=>$comment) {
            $this->addresses[$ip] = $comment;
        }
        return clone $this;
    }
    function excludeAllowedNetworks() {
        foreach ($this->addresses as $key=>$value) {
            $ip = $key;
            if(is_numeric($key)) {
                $ip = $value;
            }
            if(!preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip)) continue;
            if($this->configuration->isExcludedIp($ip)) {
                unset($this->addresses[$key]);
            }
        }
        return clone $this;
    }

    function getRulesAsArray()
    {
        $rules = [];
        $addressListName = $this->configuration->getAddressListName();
        foreach ($this->addresses as $ip => $comment) {
            if(is_numeric($ip)) {
                $ip = $comment;
            }
            $comment = Helper::idnTransform($comment);
            $rules[] = "add list={$addressListName} address={$ip}  comment={$comment}";
        }
        return $rules;
    }

    function getRulesAsText()
    {
        return join("\n", $this->getRulesAsArray()) . "\n";
    }
}